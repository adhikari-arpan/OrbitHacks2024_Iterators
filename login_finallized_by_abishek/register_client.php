<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password

    // File Upload Handling
    $photo = $_FILES['photo'];
    $photoName = uniqid() . "_" . basename($photo['name']);
    $photoPath = "uploads/clients/" . $photoName;
    move_uploaded_file($photo['tmp_name'], $photoPath);

    // Insert into Database
    $sql = "INSERT INTO client_data (name, email, phone, photo, age, gender, address, password)
            VALUES ('$name', '$email', '$phone', '$photoName', '$age', '$gender', '$address', '$password')";

    if ($conn->query($sql) === TRUE) {
        // echo "Client registered successfully!";
        header("Location:index.html");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
