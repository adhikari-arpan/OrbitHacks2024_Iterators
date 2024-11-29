import React, { useState } from 'react';
import '../styles/postlist.css';

const postitem = ({ post, onLikePost }) => {
  const [comment, setComment] = useState('');
  const [comments, setComments] = useState(post.comments || []);

  const handleComment = () => {
    if (comment) {
      setComments([...comments, comment]);
      setComment('');
    }
  };

  return (
    <div className="post-item">
      <p>{post.text}</p>
      {post.image && <img src={post.image} alt="Post" />}
      <div className="post-actions">
        <button onClick={() => onLikePost(post.id)}>Like ({post.likes})</button>
      </div>
      <div className="comments">
        <textarea
          placeholder="Add a comment..."
          value={comment}
          onChange={(e) => setComment(e.target.value)}
        />
        <button onClick={handleComment}>Comment</button>
        {comments.map((c, index) => (
          <p key={index}>{c}</p>
        ))}
      </div>
    </div>
  );
};

export default postitem;
