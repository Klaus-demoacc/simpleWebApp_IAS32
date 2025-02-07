<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id); 
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($username);

if ($stmt->num_rows > 0) {
    $stmt->fetch(); 
} else {
    echo "User not found. Please log in again.";
    session_destroy();
    header("Location: login.php");
    exit();
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #333;
            overflow: hidden;
        }

        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        nav .navbar-right {
            float: right;
        }

        .content {
            padding: 20px;
        }

        .content h1 {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <nav>
        <a href="home.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="settings.php">Settings</a>
        <div class="navbar-right">
            <a href="logout.php">Logout</a>
            <span style="color: white; padding: 14px 20px;">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
        </div>
    </nav>

    <div class="content">
        <h1>Welcome to  <?php echo htmlspecialchars($username); ?>! u bitch</h1>
        <p>Enjoy browsing your page!</p>
    </div>

</body>
</html>
