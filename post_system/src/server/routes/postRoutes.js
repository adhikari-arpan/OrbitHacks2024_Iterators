const express = require('express');
const router = express.Router();
const { addPost, getPosts } = require('../controllers/PostController');

// Route to get all posts
router.get('/', getPosts);

// Route to add a new post
router.post('/add', addPost);

module.exports = router;
