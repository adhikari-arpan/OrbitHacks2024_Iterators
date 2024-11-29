export const fetchPosts = async () => {
    // Fetch posts from the backend
  };
  
  export const createPost = async (post) => {
    // Send post data to the backend
  };
  // utils/api.js

// Function to create a post
export const creatPost = async (post) => {
    const response = await fetch('http://localhost:5000/api/posts/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(post),
    });
    if (response.ok) {
        console.log('Post added successfully!');
    } else {
        console.error('Error adding post');
    }
};

// Function to fetch posts
export const fetcPosts = async () => {
    const response = await fetch('http://localhost:5000/api/posts');
    const posts = await response.json();
    return posts;
};
