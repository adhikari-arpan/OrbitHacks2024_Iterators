import React, { useState } from 'react';
import axios from 'axios';
import "../styles/createpost.css";

const CreatePost = () => {
  const [text, setText] = useState('');
  const [image, setImage] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    await addPost(); // Trigger the post creation when form is submitted
  };

  const addPost = async () => {
    setIsSubmitting(true); // Set loading state
    setError(null); // Reset error state

    try {
      // Prepare the payload with the form input data
      const payload = {
        text: text,
        image_url: image, // Use image URL input directly
      };

      // Send POST request to backend
      const res = await axios.post("http://localhost:8000/api/posts/add", payload, {
        headers: { 'Content-Type': 'application/json' }
      });

      console.log('Post added successfully:', res.data);

      // Clear inputs after successful submission
      setText('');
      setImage('');
    } catch (error) {
      setError('Error adding post'); // Set error message if something goes wrong
      console.error(error); // Log detailed error for debugging
    } finally {
      setIsSubmitting(false); // Reset loading state after the request is complete
    }
  };

  return (
    <div className="create-post">
      <form onSubmit={handleSubmit}>
        <textarea
          placeholder="What's on your mind?"
          value={text}
          onChange={(e) => setText(e.target.value)} // Update text state
        />
        <input
          type="text"
          placeholder="Image URL" // Input field for the image URL
          value={image}
          onChange={(e) => setImage(e.target.value)} // Update image state
        />
        <button type="submit" disabled={isSubmitting}>
          {isSubmitting ? 'Posting...' : 'Post'} {/* Button text change based on submission status */}
        </button>
        {error && <p className="error">{error}</p>} {/* Display error message if exists */}
      </form>
    </div>
  );
};

export default CreatePost;
