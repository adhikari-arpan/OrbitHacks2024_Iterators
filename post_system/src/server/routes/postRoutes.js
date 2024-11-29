import { Router } from 'express';
const router = Router();
import { addPost, getPosts } from '../controllers/PostController';

// Route to get all posts
router.get('/', getPosts);

// Route to add a new post
router.post('/add', addPost);

export default router;
