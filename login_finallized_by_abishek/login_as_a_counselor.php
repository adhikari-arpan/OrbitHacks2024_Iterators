// Assuming session_start() is at the top of your login script
<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM counselor_data WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set session variables for email and role
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'counselor';  // Set role as counselor
            header("Location: counselor_profile.php");  // Redirect to the counselor profile page
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with this email.";
    }
}
?>

