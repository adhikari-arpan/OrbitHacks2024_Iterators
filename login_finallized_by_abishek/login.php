<?php
// Start the session
session_start();

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'manoratha';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Query to get user data based on email
        $stmt = $conn->prepare("SELECT * FROM Client_data WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email); // 's' means string
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password using password_verify()
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a session and set user info
                $_SESSION['user_id'] = $user['id']; // Store user ID in session
                $_SESSION['user_name'] = $user['name']; // Store user name in session
                $_SESSION['user_email'] = $user['email']; // Store user email in session
                $_SESSION['user_type'] = 'client'; // You can store whether the user is a client or counselor

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error_message'] = "Incorrect password!";
                header("Location: login.php");
                exit();
            }
        } else {
            // User with the provided email does not exist
            $_SESSION['error_message'] = "No user found with that email!";
            header("Location: login.php");
            exit();
        }
    } catch (Exception $e) {
        // Handle any errors
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: login.php");
        exit();
    } finally {
        // Close the connection
        $conn->close();
    }
} else {
    // If the form isn't submitted, just show the login page
    header("Location: login.php");
    exit();
}
?>
