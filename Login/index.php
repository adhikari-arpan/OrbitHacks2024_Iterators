<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Manoratha";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Login Form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login-submit'])) {
    $email = $_POST['login-email'];
    $password = $_POST['login-password'];
    $userType = $_POST['user-type']; // e.g., "client" or "counselor"
    
    $table = $userType === "client" ? "Clients_data" : "Counselor_data";
    
    // Prepared statement for SQL Injection prevention
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
            header("Location: dashboard.php"); // Redirect to dashboard
            exit;
        } else {
            $loginError = "Invalid password.";
        }
    } else {
        $loginError = "No user found with this email.";
    }

    $stmt->close();
}

// Handle Sign-up Form (Client)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['client-submit'])) {
    $name = $_POST['client-name'];
    $email = $_POST['client-email'];
    $phone = $_POST['client-phone'];
    $age = $_POST['client-age'];
    $gender = $_POST['client-gender'];
    $address = $_POST['client-address'];
    $password = $_POST['client-password'];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Handle photo upload
    $photo = $_FILES['client-photo'];
    $photoPath = "uploads/" . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], $photoPath);

    $sql = "INSERT INTO Clients_data (name, email, phone, age, gender, address, photo, password_hash)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssss", $name, $email, $phone, $age, $gender, $address, $photoPath, $passwordHash);

    if ($stmt->execute()) {
        $signupSuccess = "New client registered successfully.";
    } else {
        $signupError = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

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
        <!-- Login Form -->
        <div class="form-container" id="login-form-container">
            <h2>Sign in</h2>
            <form id="login-form" action="index.php" method="POST">
                <input type="email" name="login-email" placeholder="Email" required>
                <input type="password" name="login-password" placeholder="Password" required minlength="8">
                <input type="hidden" name="user-type" value="client">
                <button type="submit" name="login-submit">Sign in</button>
            </form>
            <p id="switch-form">New here? <a href="#" id="show-signup">Sign up</a></p>
            <?php if(isset($loginError)) echo "<p>$loginError</p>"; ?>
        </div>
        
        <!-- Signup Form -->
        <div class="form-container hidden" id="signup-form-container">
            <h2>Sign up as</h2>
            <div id="signup-options">
                <button class="signup-option" data-target="client-form">Client</button>
                <button class="signup-option" data-target="counselor-form">Counselor</button>
            </div>

            <!-- Client Sign-up -->
            <form id="client-form" class="signup-form hidden" action="index.php" method="POST" enctype="multipart/form-data">
                <h3>Client Sign-up</h3>
                <label for="client-name">Name</label>
                <input id="client-name" name="client-name" type="text" placeholder="Enter your name" required>
                <label for="client-email">Email</label>
                <input id="client-email" name="client-email" type="email" placeholder="Enter your email" required>
                <label for="client-phone">Phone</label>
                <input id="client-phone" name="client-phone" type="tel" placeholder="Enter your phone number" required>
                <label for="client-photo">Photo</label>
                <input id="client-photo" name="client-photo" type="file" accept="image/*" required>
                <label for="client-age">Age</label>
                <input id="client-age" name="client-age" type="number" placeholder="Enter your age" required min="18">
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
                <input id="client-password" name="client-password" type="password" placeholder="Create a strong password" required minlength="8">
                <button type="submit" name="client-submit">Sign up</button>
            </form>

            <?php if(isset($signupSuccess)) echo "<p>$signupSuccess</p>"; ?>
            <?php if(isset($signupError)) echo "<p>$signupError</p>"; ?>
            <p id="switch-form">Already have an account? <a href="#" id="show-login">Sign in</a></p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
