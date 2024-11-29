import { query as _query } from '../db';

// Add new post
const addPost = (req, res) => {
    const { text, image_url } = req.body;

    const query = 'INSERT INTO users (text, image_url) VALUES (?, ?)';
    _query(query, [text, image_url], (err, result) => {
        if (err) {
            return res.status(500).send('Error inserting data');
        }
        res.status(201).send('Post added successfully!');
    });
};

// Get all posts
const getPosts = (req, res) => {
    const query = 'SELECT * FROM users';
    _query(query, (err, results) => {
        if (err) {
            return res.status(500).send('Error fetching data');
        }
        res.json(results);
    });
};

export default { addPost, getPosts };
import connection from '../db'; // Adjust the path if necessary

