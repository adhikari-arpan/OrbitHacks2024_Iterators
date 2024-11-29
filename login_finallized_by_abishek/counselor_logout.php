<?php
session_start();  // Start the session to access session variables

// Check if the user is logged in as a counselor
if (isset($_SESSION['email']) && $_SESSION['role'] == 'counselor') {
    // Unset session variables related to the counselor
    unset($_SESSION['email']);
    unset($_SESSION['role']);
    
    // Destroy the session
    session_destroy();
    
    // Redirect to the login page or homepage
    header("Location: ./../index.html");  // Change the URL to your desired page
    exit;  // Ensure no further code runs after the redirect
}

?>
