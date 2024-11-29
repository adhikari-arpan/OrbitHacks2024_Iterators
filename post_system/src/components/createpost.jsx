//react code
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
    await addPost();
  };

  const addPost = async () => {
    setIsSubmitting(true);
    setError(null);
    try {
      const payload = {
        text,
        image_url: image, // Using image URL directly
      };

      const res = await axios.post("http://localhost:8000/api/posts/add", payload);

      console.log('Post added successfully:', res.data);

      // Clear inputs after successful submission
      setText('');
      setImage('');
    } catch (error) {
      setError('Error adding post');
      console.error(error);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="create-post">
      <form onSubmit={handleSubmit}>
        <textarea
          placeholder="What's on your mind?"
          value={text}
          onChange={(e) => setText(e.target.value)}
        />
        <input
          type="text"
          placeholder="Image URL"
          value={image}
          onChange={(e) => setImage(e.target.value)}
        />
        <button type="submit" disabled={isSubmitting}>
          {isSubmitting ? 'Posting...' : 'Post'}
        </button>
        {error && <p className="error">{error}</p>}
      </form>
    </div>
  );
};

export default CreatePost;
