<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database credentials
    $host = 'localhost';
    $user = 'root'; // Replace with your DB username
    $password = ''; // Replace with your DB password
    $dbname = 'manoratha';

    // Connect to the database
    $conn = new mysqli($host, $user, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Collect form data
    $name = $_POST['counselor-name'];
    $email = $_POST['counselor-email'];
    $phone = $_POST['counselor-phone'];
    $age = $_POST['counselor-age'];
    $gender = $_POST['counselor-gender'];
    $address = $_POST['counselor-address'];
    $about = $_POST['counselor-about'];
    $password = $_POST['counselor-password'];

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Handle file uploads
    $photo_path = 'uploads/' . basename($_FILES['counselor-photo']['name']);
    if (!move_uploaded_file($_FILES['counselor-photo']['tmp_name'], $photo_path)) {
        die('Photo upload failed.');
    }

    $resume_path = 'uploads/' . basename($_FILES['counselor-resume']['name']);
    if (!move_uploaded_file($_FILES['counselor-resume']['tmp_name'], $resume_path)) {
        die('Resume upload failed.');
    }

    // Insert into database
    $sql = "INSERT INTO counselor_data (name, email, phone, age, gender, address, photo_path, resume_path, about, password_hash) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssissssss', $name, $email, $phone, $age, $gender, $address, $photo_path, $resume_path, $about, $password_hash);

    if ($stmt->execute()) {
        echo 'Counselor registration successful.';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
