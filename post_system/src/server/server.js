const express = require('express');
const cors = require('cors');
const app = express();
const port = 5000;

// Middleware to parse incoming JSON requests
app.use(express.json());
app.use(cors());  // Enable Cross-Origin Resource Sharing for frontend requests

// Routes
const postRoutes = require('./routes/postRoutes');
app.use('/api/posts', postRoutes);

app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
