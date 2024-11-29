<?php
// include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['counselor-name'];
    $email = $_POST['counselor-email'];
    $phone = $_POST['counselor-phone'];
    $age = $_POST['counselor-age'];
    $gender = $_POST['counselor-gender'];
    $address = $_POST['counselor-address'];
    $about = $_POST['counselor-about'];
    $password = $_POST['counselor-password'];

    // Hash the password for security
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Handle photo and resume upload
    $photo = $_FILES['counselor-photo'];
    $photoPath = "uploads/" . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], $photoPath);

    $resume = $_FILES['counselor-resume'];
    $resumePath = "uploads/" . basename($resume['name']);
    move_uploaded_file($resume['tmp_name'], $resumePath);

    $sql = "INSERT INTO Counselor_data (name, email, phone, age, gender, address, photo, resume, about, password_hash)
            VALUES ('$name', '$email', '$phone', $age, '$gender', '$address', '$photoPath', '$resumePath', '$about', '$passwordHash')";

    if ($conn->query($sql) === TRUE) {
        echo "New counselor registered successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
