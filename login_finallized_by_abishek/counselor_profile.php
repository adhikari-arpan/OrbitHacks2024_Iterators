<?php
session_start();
include 'db_connect.php';

// Debugging session variables (remove in production)
echo "<pre>";
// print_r($_SESSION);
echo "</pre>";

// Check if the user is logged in and is a counselor
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'counselor') {
    echo "Access denied! You are not logged in as a counselor.";
    exit;
}

// Get counselor data
$email = $_SESSION['email'];
$sql = "SELECT * FROM counselor_data WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $counselor = $result->fetch_assoc();
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
    <title>Counselor Profile</title>
    <link rel="stylesheet" href="./counselor_profile.css"> <!-- Counselor-specific CSS -->
</head>
<body>
    <div class="profile-counselor-container">
     <div class="profile_button">
        <button><a href="./../indexwithlogin.html">Home</a></button>
     </div>
    <div class="counselor-container">
    <header class="counselor-header">
        <h1>Hello, <?= htmlspecialchars($counselor['name']) ?></h1>
        <p>Your professional profile</p>
    </header>
    <div class="counselor-profile">
        <img src="./uploads/counselors/photos/<?= htmlspecialchars($counselor['photo']) ?>" alt="Counselor Photo" class="profile-photo">
        <ul class="profile-details">
            <li><strong>Email:</strong> <?= htmlspecialchars($counselor['email']) ?></li>
            <li><strong>Phone:</strong> <?= htmlspecialchars($counselor['phone']) ?></li>
            <li><strong>Age:</strong> <?= htmlspecialchars($counselor['age']) ?></li>
            <li><strong>Gender:</strong> <?= htmlspecialchars($counselor['gender']) ?></li>
            <li><strong>Address:</strong> <?= htmlspecialchars($counselor['address']) ?></li>
            <li><strong>About:</strong> <?= htmlspecialchars($counselor['about']) ?></li>
            <li><strong>Resume:</strong> <a href="./uploads/counselors/resumes/<?= htmlspecialchars($counselor['resume']) ?>" target="_blank">View Resume</a></li>
        </ul>
    </div>
    <form action="counselor_logout.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>
    </div>

</body>
</html>
