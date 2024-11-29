<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in and is a client
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'client') {
    echo "Access denied!";
    exit;
}

// Get client data
$email = $_SESSION['email'];
$sql = "SELECT * FROM client_data WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $client = $result->fetch_assoc();
} else {
    echo "Error fetching profile data.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Profile</title>
    <link rel="stylesheet" href="./client_style.css"> <!-- Client-specific CSS -->
</head>
<body>
<div class="client-container">
    <header class="client-header">
        <h1>Welcome, <?= htmlspecialchars($client['name']) ?></h1>
        <p>Your personal profile</p>
    </header>
    <div class="client-profile">
        <img src="uploads/clients/<?= htmlspecialchars($client['photo']) ?>" alt="Client Photo" class="profile-photo">
        <ul class="profile-details">
            <li><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></li>
            <li><strong>Phone:</strong> <?= htmlspecialchars($client['phone']) ?></li>
            <li><strong>Age:</strong> <?= htmlspecialchars($client['age']) ?></li>
            <li><strong>Gender:</strong> <?= htmlspecialchars($client['gender']) ?></li>
            <li><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?></li>
        </ul>
    </div>
    <form action="client_logout.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>
</body>
</html>
