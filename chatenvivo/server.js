const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const appRep = express();
const http = require('http');
const serverClient = http.createServer(app);
const serverRep = http.createServer(appRep);
const socketIO = require('socket.io');
const mysql = require('mysql2');
const path = require('path');

// Configurar Socket.io para ambos servidores
const ioClient = socketIO(serverClient);
const ioRep = socketIO(serverRep);


app.use(express.static(__dirname));
appRep.use(express.static(__dirname));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
appRep.use(bodyParser.json());
appRep.use(bodyParser.urlencoded({ extended: false }));

// Configuración MySQL
const connection = mysql.createConnection({
    host: 'bg4rlsf9y6czzopoaxua-mysql.services.clever-cloud.com',
    user: 'ukukirf39lt6jhio',
    password: 'qy86fvwGgHBt0XFCGR3r',
    database: 'bg4rlsf9y6czzopoaxua'
});

// Conectar a MySQL
connection.connect((err) => {
    if (err) {
        console.error('Error connecting to MySQL:', err);
        return;
    }
    console.log('Connected to MySQL successfully');
});

// Preguntas predefinidas
const predefinedQuestions = [
    "¿Cuál es el problema que está experimentando?",
    "¿Cuánto tiempo lleva enfrentando este problema?",
    "¿Ha intentado alguna solución por su cuenta?",
    "¿Qué sistema operativo o dispositivo está utilizando?"
];

// Variables globales
let isRepresentativeConnected = false;

app.use('/images', express.static(path.join(__dirname, 'images')));
app.use(express.static(path.join(__dirname, 'public')));

// Agregar esta nueva ruta para el login
app.post('/login', (req, res) => {
    const { username, password, userType } = req.body;

    // Query para buscar el usuario
    const query = 'SELECT * FROM users WHERE username = ? AND password = ? AND user_type = ?';
    
    connection.query(query, [username, password, userType], (err, results) => {
        if (err) {
            console.error('Error en login:', err);
            return res.json({ success: false, message: 'Error en el servidor' });
        }

        if (results.length > 0) {
            res.json({
                success: true,
                userId: results[0].id,
                userType: results[0].user_type
            });
        } else {
            res.json({
                success: false,
                message: 'Usuario o contraseña incorrectos'
            });
        }
    });
});



app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'publiccliente', 'indexcliente.html'));
});

appRep.use(express.static(path.join(__dirname, 'publicrepresentante')));

appRep.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'publicrepresentante', 'index.html'));
});
// Ruta para guardar respuestas
app.post('/answers', (req, res) => {
    const { user_id, answers } = req.body;
    const values = answers.map((ans) => [user_id, ans.question, ans.answer]);
    const query = 'INSERT INTO pre_questions (user_id, question, answer) VALUES ?';

    connection.query(query, [values], (err) => {
        if (err) {
            console.error('Error saving answers:', err);
            res.sendStatus(500);
            return;
        }
        res.sendStatus(200);
    });
});

// Socket.io handlers
// ... (mantener el código anterior igual)

// Socket.io handlers
const activeQuestions = {};
const clientAnswers = new Map(); // Para almacenar las respuestas de cada cliente

ioClient.on('connection', (socket) => {
    console.log('Client connected');
    let clientId = null;

    if (isRepresentativeConnected) {
        socket.emit('message', {
            name: "Sistema",
            message: "Un representante se ha conectado y está disponible.",
            user_type: "system"
        });
    }

    socket.on('first-message', ({ user_id }) => {
        clientId = user_id;
        if (!activeQuestions[user_id]) {
            activeQuestions[user_id] = { index: 0, answers: [] };
            clientAnswers.set(user_id, []); // Inicializar arreglo de respuestas para este cliente
        }

        const currentQuestion = predefinedQuestions[0];
        socket.emit('question', currentQuestion);
    });

    socket.on('answer', ({ user_id, answer }) => {
        if (!activeQuestions[user_id]) {
            return;
        }

        const { index, answers } = activeQuestions[user_id];
        const currentAnswer = {
            question: predefinedQuestions[index],
            answer: answer
        };
        answers.push(currentAnswer);
        clientAnswers.get(user_id).push(currentAnswer);

        if (index + 1 < predefinedQuestions.length) {
            activeQuestions[user_id].index++;
            const nextQuestion = predefinedQuestions[index + 1];
            socket.emit('question', nextQuestion);
        } else {
            // Guardar respuestas en la base de datos
            const query = 'INSERT INTO pre_questions (user_id, question, answer) VALUES ?';
            const values = answers.map((ans) => [user_id, ans.question, ans.answer]);

            connection.query(query, [values], (err) => {
                if (err) {
                    console.error('Error saving answers:', err);
                }
            });

            socket.emit('questions-complete');
            
            // Enviar todas las respuestas acumuladas al representante
            const allAnswers = clientAnswers.get(user_id);
            ioRep.emit('client-answers', { 
                user_id, 
                answers: allAnswers,
                complete: true // Indicar que las preguntas están completas
            });

            delete activeQuestions[user_id];
        }
    });

    socket.on('message', (message) => {
        // Solo permitir mensajes si el cliente ya completó las preguntas
        if (!clientId || activeQuestions[clientId]) {
            return; // No permitir mensajes si aún hay preguntas pendientes
        }

        const query = 'INSERT INTO messages (name, message, user_type) VALUES (?, ?, ?)';
        connection.query(query, [message.name, message.message, message.user_type], (err) => {
            if (err) {
                console.error('Error saving message:', err);
                return;
            }
            ioRep.emit('message', message);
        });
    });

    socket.on('disconnect', () => {
        if (clientId) {
            clientAnswers.delete(clientId);
        }
    });
});

ioRep.on('connection', (socket) => {
    console.log('Representative connected');
    isRepresentativeConnected = true;

    // Enviar mensaje de conexión a todos los clientes
    ioClient.emit('message', {
        name: "Sistema",
        message: "Un representante se ha conectado y está disponible.",
        user_type: "system"
    });

    socket.on('message', (message) => {
        const query = 'INSERT INTO messages (name, message, user_type) VALUES (?, ?, ?)';
        connection.query(query, [message.name, message.message, message.user_type], (err) => {
            if (err) {
                console.error('Error saving message:', err);
                return;
            }
            ioClient.emit('message', message);
        });
    });

    socket.on('disconnect', () => {
        console.log('Representative disconnected');
        isRepresentativeConnected = false;
        
        ioClient.emit('message', {
            name: "Sistema",
            message: "El representante se ha desconectado.",
            user_type: "system"
        });
    });
});


// Iniciar servidores
serverClient.listen(3000, () => {
    console.log('Client server listening on http://localhost:3000');
});

serverRep.listen(3001, () => {
    console.log('Representative server listening on http://localhost:3001');
});

// Manejo de cierre
process.on('SIGINT', () => {
    connection.end(() => {
        console.log('MySQL connection closed');
        process.exit();
    });
});


yuca