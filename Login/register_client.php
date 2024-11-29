<?php
// Start the session for potential error messaging
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "Manoratha";

// Create a robust connection function
function createDatabaseConnection() {
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate uploaded file
function validateFileUpload($file, $allowedTypes, $maxFileSize) {
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Check for upload errors
    if ($fileError !== UPLOAD_ERR_OK) {
        return false;
    }

    // Check file size
    if ($fileSize > $maxFileSize) {
        return false;
    }

    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Check if file type is allowed
    if (!in_array($fileExt, $allowedTypes)) {
        return false;
    }

    return true;
}

// Handle file upload
function uploadFile($file, $uploadDir) {
    $fileName = uniqid() . '_' . $file['name'];
    $uploadPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $uploadPath;
    }

    return false;
}

// Main registration process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = sanitizeInput($_POST['client-name']);
    $email = filter_var($_POST['client-email'], FILTER_SANITIZE_EMAIL);
    $phone = sanitizeInput($_POST['client-phone']);
    $age = filter_var($_POST['client-age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = sanitizeInput($_POST['client-gender']);
    $address = sanitizeInput($_POST['client-address']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: signup.html");
        exit();
    }

    // Photo upload validation
    $allowedPhotoTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $maxPhotoSize = 5 * 1024 * 1024; // 5MB

    if (!validateFileUpload($_FILES['client-photo'], $allowedPhotoTypes, $maxPhotoSize)) {
        $_SESSION['error'] = "Invalid photo upload";
        header("Location: signup.html");
        exit();
    }

    // Upload photo
    $uploadDir = "uploads/client_photos/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $photoPath = uploadFile($_FILES['client-photo'], $uploadDir);

    if (!$photoPath) {
        $_SESSION['error'] = "Photo upload failed";
        header("Location: signup.html");
        exit();
    }

    // Hash the password (IMPORTANT: always hash passwords!)
    $password = password_hash($_POST['client-password'], PASSWORD_BCRYPT);

    // Create database connection
    $conn = createDatabaseConnection();

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Client_data (name, email, phone, age, gender, address, photo, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissss", $name, $email, $phone, $age, $gender, $address, $photoPath, $password);

    try {
        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful!";
            header("Location: login.php");
            exit();
        } else {
            throw new Exception("Registration failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: signup.html");
        exit();
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly without POST
    header("Location: signup.html");
    exit();
}
?>