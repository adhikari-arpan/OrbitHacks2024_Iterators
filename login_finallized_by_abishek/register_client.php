<?php
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

try {
    // Get form data
    $name = $_POST['client-name'];
    $email = $_POST['client-email'];
    $phone = $_POST['client-phone'];
    $age = intval($_POST['client-age']);
    $gender = $_POST['client-gender'];
    $address = $_POST['client-address'];
    $password = $_POST['client-password']; // Plain password from the form

    // Validate age range (18-120)
    if ($age < 18 || $age > 120) {
        throw new Exception('Age must be between 18 and 120.');
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Handle photo upload
    $photo = $_FILES['client-photo'];
    $upload_dir = 'uploads/';
    $photo_name = time() . '_' . basename($photo['name']);
    $photo_path = $upload_dir . $photo_name;

    if (!move_uploaded_file($photo['tmp_name'], $photo_path)) {
        throw new Exception('Failed to upload photo.');
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare(
        "INSERT INTO Client_data (name, email, phone, photo, age, gender, address, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssisss", $name, $email, $phone, $photo_path, $age, $gender, $address, $hashed_password);

    $stmt->execute();

    echo "Client registered successfully!";
} catch (Exception $e) {
    // Handle errors
    echo "Error: " . $e->getMessage();
} finally {
    // Close the connection
    $conn->close();
}
?>
