const db = require('../db');

// Add new post
const addPost = (req, res) => {
    const { text, image_url } = req.body;

    const query = 'INSERT INTO posts (text, image_url) VALUES (?, ?)';
    db.query(query, [text, image_url], (err, result) => {
        if (err) {
            return res.status(500).send('Error inserting data');
        }
        res.status(201).send('Post added successfully!');
    });
};

// Get all posts
const getPosts = (req, res) => {
    const query = 'SELECT * FROM posts';
    db.query(query, (err, results) => {
        if (err) {
            return res.status(500).send('Error fetching data');
        }
        res.json(results);
    });
};

module.exports = { addPost, getPosts };
