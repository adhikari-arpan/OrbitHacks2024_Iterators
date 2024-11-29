<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $about = $_POST['about'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password

    // File Upload Handling
    $photo = $_FILES['photo'];
    $resume = $_FILES['resume'];
    $photoName = uniqid() . "_" . basename($photo['name']);
    $resumeName = uniqid() . "_" . basename($resume['name']);
    $photoPath = "uploads/counselors/photos/" . $photoName;
    $resumePath = "uploads/counselors/resumes/" . $resumeName;  // Fix missing slash

    // Validate file uploads (optional)
    if (in_array($photo['type'], ['image/jpeg', 'image/png', 'image/jpg']) && $photo['size'] < 5000000) {
        move_uploaded_file($photo['tmp_name'], $photoPath);
    } else {
        echo "Invalid photo file. Allowed types: JPEG, PNG. Max size: 5MB.";
        exit;
    }

    if (in_array($resume['type'], ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']) && $resume['size'] < 5000000) {
        move_uploaded_file($resume['tmp_name'], $resumePath);
    } else {
        echo "Invalid resume file. Allowed types: PDF, DOC, DOCX. Max size: 5MB.";
        exit;
    }

    // Prepare SQL query using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO counselor_data (name, email, phone, photo, resume, age, gender, address, about, password)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind the parameters to the prepared statement
    $stmt->bind_param("ssssssssss", $name, $email, $phone, $photoName, $resumeName, $age, $gender, $address, $about, $password);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Counselor registered successfully!";
        header("Location: index.html");  // Redirect to the home page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>
