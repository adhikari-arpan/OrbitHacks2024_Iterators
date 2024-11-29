<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manoratha - Login/Sign-up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container" id="login-form-container">
            <h2>Sign in</h2>
            <form id="login-form" action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required minlength="3" maxlength="50">
                <input type="password" name="password" placeholder="Password" required minlength="8">
                <button type="submit">Sign in</button>
            </form>
            <p id="switch-form">New here? <a href="#" id="show-signup">Sign up</a></p>
        </div>
        
        <!-- database connection -->
        <?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "Manoratha";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
?>


<?php
// include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['login-email'];
    $password = $_POST['login-password'];
    $userType = $_POST['user-type']; // e.g., "client" or "counselor"

    $table = $userType === "client" ? "Clients_data" : "Counselor_data";

    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password_hash'])) {
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $userType;
            $_SESSION['user_name'] = $row['name'];

            echo "Login successful. Redirecting...";
            header("Location: dashboard.php"); // Redirect to the dashboard
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>






        <div class="form-container hidden" id="signup-form-container">
            <h2>Sign up as</h2>
            <div id="signup-options">
                <button class="signup-option" data-target="client-form">Client</button>
                <button class="signup-option" data-target="counselor-form">Counselor</button>
            </div>

            <form id="client-form" class="signup-form hidden" action="register_client.php" method="post" enctype="multipart/form-data">
                <h3>Client Sign-up</h3>
                <label for="client-name">Name</label>
                <input id="client-name" name="client-name" type="text" placeholder="Enter your name" required minlength="2" maxlength="100">
                
                <label for="client-email">Email</label>
                <input id="client-email" name="client-email" type="email" placeholder="Enter your email" required>
                
                <label for="client-phone">Phone</label>
                <input id="client-phone" name="client-phone" type="tel" placeholder="Enter your phone number" required pattern="[0-9]{10}">
                
                <label for="client-photo">Photo</label>
                <input id="client-photo" name="client-photo" type="file" accept="image/*" required>
                
                <label for="client-age">Age</label>
                <input id="client-age" name="client-age" type="number" placeholder="Enter your age" required min="18" max="120">
                
                <label for="client-gender">Gender</label>
                <select id="client-gender" name="client-gender" required>
                    <option value="">Select your gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                
                <label for="client-address">Address</label>
                <input id="client-address" name="client-address" type="text" placeholder="Enter your address" required>
                
                <label for="client-password">Password</label>
                <input id="client-password" name="client-password" type="password" placeholder="Create a strong password" required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                
                <button type="submit">Sign up</button>
            </form>

            <!-- client -->
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





            <form id="counselor-form" class="signup-form hidden" action="./register_counselor.php" method="post" enctype="multipart/form-data">
                <h3>Counselor Sign-up</h3>
                <label for="counselor-name">Name</label>
                <input id="counselor-name" name="counselor-name" type="text" placeholder="Enter your name" required minlength="2" maxlength="100">
                
                <label for="counselor-email">Email</label>
                <input id="counselor-email" name="counselor-email" type="email" placeholder="Enter your email" required>
                
                <label for="counselor-phone">Phone</label>
                <input id="counselor-phone" name="counselor-phone" type="tel" placeholder="Enter your phone number" required pattern="[0-9]{10}">
                
                <label for="counselor-age">Age</label>
                <input id="counselor-age" name="counselor-age" type="number" placeholder="Enter your age" required min="25" max="120">
                
                <label for="counselor-gender">Gender</label>
                <select id="counselor-gender" name="counselor-gender" required>
                    <option value="">Select your gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                
                <label for="counselor-address">Address</label>
                <input id="counselor-address" name="counselor-address" type="text" placeholder="Enter your address" required>
                
                <label for="counselor-photo">Photo</label>
                <input id="counselor-photo" name="counselor-photo" type="file" accept="image/*" required>
                
                <label for="counselor-resume">Resume</label>
                <input id="counselor-resume" name="counselor-resume" type="file" accept=".pdf,.doc,.docx" required>
                
                <label for="counselor-about">About</label>
                <textarea id="counselor-about" name="counselor-about" placeholder="Tell us about yourself" required minlength="50" maxlength="500"></textarea>
                
                <label for="counselor-password">Password</label>
                <input id="counselor-password" name="counselor-password" type="password" placeholder="Create a strong password" required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                
                <button type="submit">Sign up</button>
            </form>



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

            <p id="switch-form">Already have an account? <a href="#" id="show-login">Sign in</a></p>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>