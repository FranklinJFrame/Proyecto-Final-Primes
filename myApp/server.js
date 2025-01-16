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

// Ruta de inicio de sesión
app.post('/login', (req, res) => {
    const { username, password } = req.body;

    // Verificar usuario en la base de datos
    const query = 'SELECT * FROM users WHERE username = ? AND password = ?';
    connection.query(query, [username, password], (err, results) => {
        if (err) {
            console.error('Error querying the database:', err);
            return res.status(500).json({ success: false, message: 'Error interno del servidor' });
        }

        if (results.length > 0) {
            const user = results[0];
            // Redirigir según el rol
            const redirectUrl = user.role === 'client' ? 'http://localhost:3000' : 'http://localhost:3001';
            res.json({ success: true, redirectUrl });
        } else {
            res.json({ success: false, message: 'Usuario o contraseña incorrectos' });
        }
    });
});

// Rutas compartidas
function setupRoutes(app, io, userType) {
    app.get('/messages', (req, res) => {
        connection.query('SELECT * FROM messages ORDER BY created_at DESC LIMIT 100', (err, results) => {
            if (err) {
                console.error('Error fetching messages:', err);
                res.sendStatus(500);
                return;
            }
            res.send(results);
        });
    });

    app.post('/messages', (req, res) => {
        const message = {
            name: req.body.name,
            message: req.body.message,
            user_type: userType
        };

        connection.query('INSERT INTO messages SET ?', message, (err, results) => {
            if (err) {
                console.error('Error saving message:', err);
                res.sendStatus(500);
                return;
            }

            console.log('Message saved to database');
            ioClient.emit('message', message);
            ioRep.emit('message', message);
            res.sendStatus(200);
        });
    });
}

// Configurar rutas para ambos servidores
setupRoutes(app, ioClient, 'client');
setupRoutes(appRep, ioRep, 'representative');

// Socket.io handlers
ioClient.on('connection', (socket) => {
    console.log('Client connected');
});

ioRep.on('connection', (socket) => {
    console.log('Representative connected');
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
