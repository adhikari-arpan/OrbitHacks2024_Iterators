<?php
// Database connection
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password (use your actual password if any)
$dbname = "doctor_appointments"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $cost = $_POST['cost'];
    $available_time = $_POST['available_time'];
    $date_posted = $_POST['date_posted'];

    // Handle file upload (doctor's image)
    $target_dir = "uploads/"; // Folder where the image will be stored
    $target_file = $target_dir . basename($_FILES["doctor_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if the uploaded file is an image
    $check = getimagesize($_FILES["doctor_image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["doctor_image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (JPG, JPEG, PNG, GIF)
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // If everything is ok, try to upload the file
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["doctor_image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["doctor_image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Insert data into the database
    if ($uploadOk == 1) {
        $image_path = $target_file; // Store the image path in the database

        $sql = "INSERT INTO services (title, description, cost, available_time, date_posted, doctor_image)
                VALUES ('$title', '$description', '$cost', '$available_time', '$date_posted', '$image_path')";

        if ($conn->query($sql) === TRUE) {
            echo "New service posted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>
