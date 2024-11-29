<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login/Sign-up</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <div class="form-container">
      <h2 id="form-title">Sign in</h2>
      <form id="login-form">
        <input type="text" placeholder="Username" required>
        <input type="password" placeholder="Password" required>
        <button type="submit">Sign in</button>
      </form>
      <p id="switch-form">New here? <a href="#" id="show-signup">Sign up</a></p>
    </div>
    <div class="form-container hidden" id="signup-form-container">
      <h2 id="form-title">Sign up as</h2>
      <div id="signup-options">
        <button class="signup-option" data-target="client-form">Client</button>
        <button class="signup-option" data-target="counselor-form">Counselor</button>
      </div>
      <form id="client-form" class="signup-form hidden">
        <h3>Client Sign-up</h3>
        <label for="client-name">Name</label>
        <input id="client-name" type="text" placeholder="Enter your name" required>
        <label for="client-email">Email</label>
        <input id="client-email" type="email" placeholder="Enter your email" required>
        <label for="client-phone">Phone</label>
        <input id="client-phone" type="tel" placeholder="Enter your phone number" required>
        <label for="client-photo">Photo</label>
        <input id="client-photo" type="file" placeholder="Upload a photo" required>
        <label for="client-age">Age</label>
        <input id="client-age" type="number" placeholder="Enter your age" required>
        <label for="client-gender">Gender</label>
        <select id="client-gender" name="gender" required>
          <option value="">Select your gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
        <br>
        <label for="client-address">Address</label>
        <input id="client-address" type="text" placeholder="Enter your address" required>

       
        <label for="client-password">Password</label>
        <input id="client-password" type="password" name="client-password" placeholder="Create a new strong Password" required>
    </div>
        
        <button type="submit">Sign up</button>
      </form>
<!-- iserting client data into database -->
      <?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Set your database password
$dbname = "Manoratha";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['client-name'];
    $phone = $_POST['client-phone'];
    $age = $_POST['client-age'];
    $gender = $_POST['client-gender'];
    $address = $_POST['client-address'];
    $email = $_POST['client-email'];

    // Handle file upload for photo
    $photo = $_FILES['client-photo'];
    $photoName = $photo['name'];
    $photoTmpName = $photo['tmp_name'];
    $photoFolder = "uploads/" . basename($photoName);

    // Move the uploaded file to the 'uploads' directory
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (move_uploaded_file($photoTmpName, $photoFolder)) {
        // Insert data into the database
        $sql = "INSERT INTO Client (name, phone, photo, age, gender, address, email) 
                VALUES ('$name', '$phone', '$photoFolder', $age, '$gender', '$address', '$email')";

        if ($conn->query($sql) === TRUE) {
            echo "Sign-up successful! Your data has been saved.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload photo.";
    }
}

$conn->close();
?>






      <form id="counselor-form" class="signup-form hidden">
        
        <label for="counselor-name">Name</label>
        <input id="counselor-name" type="text" placeholder="Enter your name" required>
        <label for="counselor-email">Email</label>
        <input id="counselor-email" type="email" placeholder="Enter your email" required>
        <label for="counselor-phone">Phone</label>
        <input id="counselor-phone" type="tel" placeholder="Enter your phone number" required>
        <label for="counselor-age">Age</label>
        <input id="counselor-age" type="number" placeholder="Enter your age" required>
        <label for="counselor-gender">Gender</label>
        <select id="counselor-gender" name="gender" required>
          <option value="">Select your gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
        <br>
        <label for="counselor-address">Address</label>
        <input id="counselor-address" type="text" placeholder="Enter your address" required>
        <label for="counselor-photo">Photo</label>
        <input id="counselor-photo" type="file" placeholder="Upload a photo" required>
        
        <label for="counselor-resume">Resume</label>
        <input id="counselor-resume" type="file" placeholder="Upload your resume" required>
        <label for="counselor-about">About</label>
        <textarea id="counselor-about" placeholder="Tell us about yourself" required></textarea>
        <label for="counselor-password">Password</label>
        <input id="counselor-password" type="password" name="counselor-password" placeholder="Create a new strong Password" required>
        <button type="submit">Sign up</button>
      </form>




    <!-- counselor data storing to database  -->




    <?php
    $message = ""; // To store success/error messages

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Manoratha";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Collect form data
        $name = $_POST['counselor-name'];
        $phone = $_POST['counselor-phone'];
        $age = $_POST['counselor-age'];
        $gender = $_POST['counselor-gender'];
        $address = $_POST['counselor-address'];
        $email = $_POST['counselor-email'];
        $about = $_POST['counselor-about'];

        // Handle file uploads
        $photo = $_FILES['counselor-photo']['name'];
        $resume = $_FILES['counselor-resume']['name'];

        $photo_target = "uploads/" . basename($photo);
        $resume_target = "uploads/" . basename($resume);

        move_uploaded_file($_FILES['counselor-photo']['tmp_name'], $photo_target);
        move_uploaded_file($_FILES['counselor-resume']['tmp_name'], $resume_target);

        // Insert query
        $sql = "INSERT INTO Counselor_data (Name, Email, Phone, Age, Gender, Address, Photo, Resume, About) 
                VALUES ('$name', '$email', '$phone', '$age', '$gender', '$address', '$photo', '$resume', '$about')";

        if ($conn->query($sql) === TRUE) {
            $message = "New record created successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }

        $conn->close();
    }
    ?>
      <p id="switch-form">Already have an account? <a href="#" id="show-login">Sign in</a></p>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>