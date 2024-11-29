import React, { useState, useEffect } from 'react';
import Header from './components/header';
import CreatePost from './components/createpost';
import PostList from './components/postlist';
import './styles/App.css';
import axios from "axios";

function App() {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true); // Loading state
  const [error, setError] = useState(null); // Error state

  useEffect(() => {
    const fetchPosts = async () => {
      try {
        const res = await axios.get("http://localhost:8000/api/posts");
        console.log(res.data)
        setPosts(res.data);
      } catch (error) {
        setError('Error fetching posts');
        console.error(error);
      } finally {
        setLoading(false);
      }
    };

    fetchPosts();
  }, []);



  const addPosts = (newPost) => {
    setPosts([newPost, ...posts]);
  };

  const likePost = (postId) => {
    setPosts(
      posts.map((post) =>
        post.id === postId ? { ...post, likes: post.likes + 1 } : post
      )
    );
  };

  if (loading) return <div>Loading...</div>; // Loading state
  if (error) return <div>{error}</div>; // Error state

  return (
    <div className="App">
      <Header />
      <CreatePost />
      <PostList posts={posts} onLikePost={likePost} />
    </div>
  );
}

export default App;
