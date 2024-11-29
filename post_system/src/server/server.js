import express from 'express';
import { createConnection } from 'mysql2';
import multer from 'multer';
import cors from 'cors';

const app = express();
const port = 8000;

// Use express.json() middleware to parse JSON requests
app.use(express.json()); // Parse JSON bodies for POST requests
app.use(cors()); // Enable Cross-Origin Request

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

    // Log the received body for debugging
    console.log('Received Body:', req.body);

    // Validate the input fields (Ensure text and image_url are provided)
    if (!text || !image_url) {
        console.error('Validation failed: Missing text or image_url');
        return res.status(400).send('Both text and image_url are required');
    }

    // SQL query to insert data into the posts table
    const query = `INSERT INTO posts (text, image_url) VALUES (?, ?)`;

    // Execute the SQL query
    con.query(query, [text, image_url], (err, result) => {
        if (err) {
            console.error('Error inserting data: ', err);
            return res.status(500).send('Error inserting data');
        }
        console.log('Post added successfully');
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
