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

// Middleware
app.use(express.static(__dirname));
appRep.use(express.static(__dirname));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
appRep.use(bodyParser.json());
appRep.use(bodyParser.urlencoded({ extended: false }));

// Configuración MySQL
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'franklin',
    database: 'angel1'
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

// Rutas específicas para cada vista
app.get('/login', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'login.html'));
});

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'publiccliente', 'indexcliente.html'));
});

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
const activeQuestions = {};

ioClient.on('connection', (socket) => {
    console.log('Client connected');

    if (isRepresentativeConnected) {
        socket.emit('message', {
            name: "Sistema",
            message: "Un representante se ha conectado y está disponible.",
            user_type: "system"
        });
    }

    socket.on('first-message', ({ user_id }) => {
        if (!activeQuestions[user_id]) {
            activeQuestions[user_id] = { index: 0, answers: [] };
        }

        const currentQuestion = predefinedQuestions[0];
        socket.emit('question', currentQuestion);
    });

    socket.on('answer', ({ user_id, answer }) => {
        if (!activeQuestions[user_id]) {
            return;
        }

        const { index, answers } = activeQuestions[user_id];
        answers.push({ question: predefinedQuestions[index], answer });

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
            ioRep.emit('client-answers', { user_id, answers });

            delete activeQuestions[user_id];
        }
    });

    socket.on('message', (message) => {
        const query = 'INSERT INTO messages (name, message, user_type) VALUES (?, ?, ?)';
        connection.query(query, [message.name, message.message, message.user_type], (err) => {
            if (err) {
                console.error('Error saving message:', err);
                return;
            }
            // Emitir el mensaje a todos los sockets conectados
            ioClient.emit('message', message);
            ioRep.emit('message', message);
        });
    });
});

ioRep.on('connection', (socket) => {
    console.log('Representative connected');
    isRepresentativeConnected = true;

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
            // Emitir el mensaje a todos los sockets conectados
            ioClient.emit('message', message);
            ioRep.emit('message', message);
        });
    });

    socket.on('disconnect', () => {
        console.log('Representative disconnected');
        isRepresentativeConnected = false;
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
