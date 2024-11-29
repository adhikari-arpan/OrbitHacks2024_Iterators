import React from 'react';
import PostItem from './postitem';
import '../styles/postlist.css';

const postlist = ({ posts, onLikePost }) => {
  return (
    <div className="post-list">
      {posts.map((post) => (
        <PostItem key={post.id} post={post} onLikePost={onLikePost} />
      ))}
    </div>
  );
};

export default postlist;
