import React, { useState } from 'react';
import Header from './components/header';
import CreatePost from './components/createpost';
import PostList from './components/postlist';
import './styles/App.css';

function App() {
  const [posts, setPosts] = useState([]);

  const addPost = (newPost) => {
    setPosts([newPost, ...posts]);
  };

  const likePost = (postId) => {
    setPosts(
      posts.map((post) =>
        post.id === postId ? { ...post, likes: post.likes + 1 } : post
      )
    );
  };

  return (
    <div className="App">
      <Header />
      <CreatePost onAddPost={addPost} />
      <PostList posts={posts} onLikePost={likePost} />
    </div>
  );
}

export default App;
