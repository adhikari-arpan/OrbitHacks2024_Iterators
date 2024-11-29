<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$user_name = $_SESSION['user_name'];

// Connect to the database
include 'db_connection.php';

// Fetch user data based on user type
if ($user_type == "client") {
    $sql = "SELECT * FROM Clients_data WHERE id = ?";
} else {
    $sql = "SELECT * FROM Counselor_data WHERE id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $user_name; ?>!</h1>

    <h2>User Profile</h2>
    <p>Name: <?php echo $user_data['name']; ?></p>
    <p>Email: <?php echo $user_data['email']; ?></p>
    <p>Phone: <?php echo $user_data['phone']; ?></p>
    <p>Age: <?php echo $user_data['age']; ?></p>
    <p>Gender: <?php echo ucfirst($user_data['gender']); ?></p>
    <p>Address: <?php echo $user_data['address']; ?></p>

    <h3>Profile Picture</h3>
    <?php if ($user_data['photo']) { ?>
        <img src="uploads/<?php echo $user_data['photo']; ?>" alt="Profile Picture" width="150">
    <?php } else { ?>
        <p>No profile picture uploaded.</p>
    <?php } ?>

    <br><br>

    <a href="update_profile.php">Update Profile</a>
    <br>
    <a href="change_password.php">Change Password</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
