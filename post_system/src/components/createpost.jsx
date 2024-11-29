import React, { useState } from 'react';
import "../styles/createpost.css";


const createpost = ({ onAddPost }) => {
  const [text, setText] = useState('');
  const [image, setImage] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    if (text || image) {
      const newPost = {
        id: Date.now(),
        text,
        image,
        likes: 0,
        comments: [],
      };
      onAddPost(newPost);
      setText('');
      setImage('');
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
          type="file"
          onChange={(e) => setImage(URL.createObjectURL(e.target.files[0]))}
        />
        <button type="submit">Post</button>
      </form>
    </div>
  );
};

export default createpost;
