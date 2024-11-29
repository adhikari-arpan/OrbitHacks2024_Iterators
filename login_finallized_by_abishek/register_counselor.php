<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
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
    // Capture form data
    $name = $_POST['counselor-name'];
    $email = $_POST['counselor-email'];
    $phone = $_POST['counselor-phone'];
    $age = $_POST['counselor-age'];
    $gender = $_POST['counselor-gender'];
    $address = $_POST['counselor-address'];
    $about = $_POST['counselor-about'];
    $password = $_POST['counselor-password'];

    // Initialize variables for file uploads
    $uploadDir = "uploads/";
    $photoPath = $uploadDir . basename($_FILES['counselor-photo']['name']);
    print($uploadDir);
    $resumePath = $uploadDir . basename($_FILES['counselor-resume']['name']);


//     $photo = $_FILES['counselor-photo'];
// $photoPath = "uploads/" . basename($photo['name']);

// if ($_FILES['counselor-photo']['error'] === UPLOAD_ERR_OK) {
//     if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
//         echo "Failed to upload photo! Possible reasons: permissions issue, path error.";
//         echo "Temporary file path: " . $photo['tmp_name'] . "<br>";
//         echo "Target file path: " . $photoPath . "<br>";
//         exit;
//     }
// } else {
//     echo "File upload error: " . $_FILES['counselor-photo']['error'];
//     exit;
// }


    // Validate and upload photo
    // if ($_FILES['counselor-photo']['error'] === UPLOAD_ERR_OK) {
    //     if (!move_uploaded_file($_FILES['counselor-photo']['tmp_name'], $photoPath)) {
    //         echo "Failed to upload photo!";
    //         exit;
    //     }
    // } else {
    //     echo "Error uploading photo: " . $_FILES['counselor-photo']['error'];
    //     exit;
    // }

    // // Validate and upload resume
    // if ($_FILES['counselor-resume']['error'] === UPLOAD_ERR_OK) {
    //     if (!move_uploaded_file($_FILES['counselor-resume']['tmp_name'], $resumePath)) {
    //         echo "Failed to upload resume!";
    //         exit;
    //     }
    // } else {
    //     echo "Error uploading resume: " . $_FILES['counselor-resume']['error'];
    //     exit;
    // }

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert counselor data
    $sql = "INSERT INTO Counselor_data(name, email, phone, age, gender, address,  about, password_hash) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Bind parameters
    $stmt->bind_param(
        "sssiisss",
        $name,
        $email,
        $phone,
        $age,
        $gender,
        $address,
        $about,
        $hashedPassword
    );

    // Execute query
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
