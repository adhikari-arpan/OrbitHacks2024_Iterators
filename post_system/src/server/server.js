import express from 'express';
import { createConnection } from 'mysql2';
import multer from 'multer';
import cors from 'cors';
const app = express();
app.use(cors());
const port = 8000;

// Configure multer for form data parsing
const upload = multer();

app.use(upload.none()); // Use multer to parse form data without files

// MySQL connection
const con = createConnection({
    host: 'localhost',
    user: 'root',
    password: '',  // Your MySQL password
    database: 'mentalhealth'
});

// Connecting to MySQL
con.connect((err) => {
    if (err) {
        console.error('Error connecting to the database: ', err.stack);
        return;
    }
    console.log('Connected to the database!');
});

// POST route to add a new post
app.post('/api/posts/add', (req, res) => {
    const { text, image_url } = req.body; // Accessing text and image_url from req.body
    console.log(req.body); // Log the received body for debugging

    // Make sure to use proper SQL syntax
    const query = `INSERT INTO posts (text, image_url) VALUES (?, ?)`;

    con.query(query, [text, image_url], (err, result) => {
        if (err) {
            console.error('Error inserting data: ', err);
            return res.status(500).send('Error inserting data');
        }
        res.status(201).send('Post added successfully!');
    });
});

// GET route to fetch posts
app.get('/api/posts', (req, res) => {
    const query = 'SELECT * FROM posts';
    con.query(query, (err, results) => {
        if (err) {
            console.error('Error fetching data: ', err);
            return res.status(500).send('Error fetching data');
        }
        res.json(results);
    });
});

// Server listening
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
