<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Manoratha";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['client-name'];
    $email = $_POST['client-email'];
    $phone = $_POST['client-phone'];
    $age = $_POST['client-age'];
    $gender = $_POST['client-gender'];
    $address = $_POST['client-address'];
    $password = $_POST['client-password'];
    
    // Handle file uploads
    $photo = $_FILES['client-photo'];
    $photoPath = "uploads/" . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], $photoPath);

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data into the Clients_data table
    $sql = "INSERT INTO Clients_data (name, email, phone, age, gender, address, photo, password_hash)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiisss", $name, $email, $phone, $age, $gender, $address, $photoPath, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
