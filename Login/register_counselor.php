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
    $name = sanitizeInput($_POST['counselor-name']);
    $email = filter_var($_POST['counselor-email'], FILTER_SANITIZE_EMAIL);
    $phone = sanitizeInput($_POST['counselor-phone']);
    $age = filter_var($_POST['counselor-age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = sanitizeInput($_POST['counselor-gender']);
    $address = sanitizeInput($_POST['counselor-address']);
    $about = sanitizeInput($_POST['counselor-about']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: signup.html");
        exit();
    }

    // Photo upload validation
    $allowedPhotoTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $maxPhotoSize = 5 * 1024 * 1024; // 5MB

    if (!validateFileUpload($_FILES['counselor-photo'], $allowedPhotoTypes, $maxPhotoSize)) {
        $_SESSION['error'] = "Invalid photo upload";
        header("Location: signup.html");
        exit();
    }

    // Resume upload validation
    $allowedResumeTypes = ['pdf', 'doc', 'docx'];
    $maxResumeSize = 10 * 1024 * 1024; // 10MB

    if (!validateFileUpload($_FILES['counselor-resume'], $allowedResumeTypes, $maxResumeSize)) {
        $_SESSION['error'] = "Invalid resume upload";
        header("Location: signup.html");
        exit();
    }

    // Upload photo and resume
    $photoUploadDir = "uploads/counselor_photos/";
    $resumeUploadDir = "uploads/counselor_resumes/";

    // Create upload directories if they don't exist
    if (!is_dir($photoUploadDir)) mkdir($photoUploadDir, 0777, true);
    if (!is_dir($resumeUploadDir)) mkdir($resumeUploadDir, 0777, true);

    $photoPath = uploadFile($_FILES['counselor-photo'], $photoUploadDir);
    $resumePath = uploadFile($_FILES['counselor-resume'], $resumeUploadDir);

    if (!$photoPath || !$resumePath) {
        $_SESSION['error'] = "File upload failed";
        header("Location: signup.html");
        exit();
    }

    // Hash the password (IMPORTANT: always hash passwords!)
    $password = password_hash($_POST['counselor-password'], PASSWORD_BCRYPT);

    // Create database connection
    $conn = createDatabaseConnection();

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Counselor_data (Name, Email, Phone, Age, Gender, Address, Photo, Resume, About, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisisssss", $name, $email, $phone, $age, $gender, $address, $photoPath, $resumePath, $about, $password);

    try {
        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Counselor registration successful!";
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