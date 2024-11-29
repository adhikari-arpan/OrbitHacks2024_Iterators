<?php
session_start();  // Start the session to track the user's login status
include 'db_connect.php';  // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind the SQL query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM client_data WHERE email = ?");
    $stmt->bind_param("s", $email);  // 's' stands for string parameter

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a row with the given email exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Start a session and store user data for later use
            $_SESSION['email'] = $email;  // Store email in session
            $_SESSION['role'] = 'client';  // Store user role

            // Redirect to the client's profile page
            header("Location: ./client_profile.php");
            exit;  // Always call exit after redirecting
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with this email.";
    }

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
}
?>

?>
