<?php
// Database connection
$servername = "localhost"; // Change this to your database server
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "manoratha";     // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $name = $_POST['counselor-name'];
    $email = $_POST['counselor-email'];
    $phone = $_POST['counselor-phone'];
    $age = $_POST['counselor-age'];
    $gender = $_POST['counselor-gender'];
    $address = $_POST['counselor-address'];
    $about = $_POST['counselor-about'];
    $password = password_hash($_POST['counselor-password'], PASSWORD_DEFAULT); // Hash the password

    // File uploads for photo and resume
    $photo = $_FILES['counselor-photo']['name'];
    $photo_temp = $_FILES['counselor-photo']['tmp_name'];
    $resume = $_FILES['counselor-resume']['name'];
    $resume_temp = $_FILES['counselor-resume']['tmp_name'];

    // Set target directories for file uploads
    $photo_dir = "uploads/counselor/photos/" . basename($photo);
    $resume_dir = "uploads/counselor/resumes/" . basename($resume);

    // Move files to their respective directories
    if (move_uploaded_file($photo_temp, $photo_dir) && move_uploaded_file($resume_temp, $resume_dir)) {
        // Prepare SQL query to insert data into the database
        $sql = "INSERT INTO counselor_data (name, email, phone, age, gender, address, photo, resume, about, password)
                VALUES ('$name', '$email', '$phone', '$age', '$gender', '$address', '$photo_dir', '$resume_dir', '$about', '$password')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "Counselor registered successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading files.";
    }
}

// Close connection
$conn->close();
?>
