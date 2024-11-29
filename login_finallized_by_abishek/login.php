<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Manoratha";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];  // Assuming username is the email
    $password = $_POST['password'];
    $userType = $_POST['user-type']; // 'client' or 'counselor'

    $table = $userType === "client" ? "Clients_data" : "Counselor_data";

    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password_hash'])) {
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $userType;
            $_SESSION['user_name'] = $row['name'];

            echo "Login successful. Redirecting...";
            header("Location: dashboard.php");  // Redirect to the dashboard
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    $stmt->close();
}
$conn->close();
?>
