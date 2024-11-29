<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in (i.e., the user_id session variable should exist)
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit();
}

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

// Fetch user details from the database using the session's user_id
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM Client_data WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        .card {
            background-color: #fff;
            padding: 20px;
            margin: 15px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card h3 {
            margin-top: 0;
        }
        .btn-logout {
            display: inline-block;
            background-color: red;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn-logout:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>Welcome to Your Dashboard</h1>
    </div>

    <div class="container">
        <div class="card">
            <h3>User Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        </div>

        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

</body>
</html>
