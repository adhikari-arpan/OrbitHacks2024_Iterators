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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted data
    $email = trim($_POST['username']); // Trim to remove extra spaces
    $input_password = $_POST['password']; // The password entered by the user

    try {
        // Prepare the SQL query to check for matching email in the database
        $stmt = $conn->prepare("SELECT * FROM Client_data WHERE email = ?");
        $stmt->bind_param("s", $email); // 's' means string
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the email exists in the database
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password using password_verify()
            if (password_verify($input_password, $user['password'])) {
                // Password is correct, start session and redirect to home section
                $_SESSION['user_id'] = $user['id']; // Store user ID in session
                $_SESSION['username'] = $user['name']; // Store username in session
                header("Location: ../../indexwithlogin.html"); // Redirect to home section
                exit();
            } else {
                // Incorrect password
                $error_message = "Invalid password.";
            }
        } else {
            // Email not found in the database
            $error_message = "No account found with that email address.";
        }
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

$conn->close();
?>
