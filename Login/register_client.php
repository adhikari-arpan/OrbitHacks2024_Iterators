<?php
// include 'db_connection.php';

if ($conn == true) {
    $name = $_POST['client-name'];
    $email = $_POST['client-email'];
    $phone = $_POST['client-phone'];
    $age = $_POST['client-age'];
    $gender = $_POST['client-gender'];
    $address = $_POST['client-address'];
    $password = $_POST['client-password'];

    // Hash the password for security
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Handle photo upload
    $photo = $_FILES['client-photo'];
    $photoPath = "uploads/" . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], $photoPath);

    $sql = "INSERT INTO Clients_data (name, email, phone, age, gender, address, photo, password_hash)
            VALUES ('$name', '$email', '$phone', $age, '$gender', '$address', '$photoPath', '$passwordHash')";

    if ($conn->query($sql) === TRUE) {
        echo "New client registered successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
