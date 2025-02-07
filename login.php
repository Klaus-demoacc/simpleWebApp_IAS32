<?php
include 'db.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>Invalid email format.</p>";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: home.php");
                exit();
            } else {
                echo "<p style='color: bb86fc;'>Invalid password.</p>";
            }
        } else {
            echo "<p style='color: bb86fc;'>No user found with that email.</p>";
        }

        $stmt->close();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: Arial, sans-serif;
            flex-direction: column; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            
        }
        form {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%; /* Responsive width */
            max-width: 400px; /* Maximum width */
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px -8px;
            border: none;
            border-radius: 4px;
            background-color: #333333;
            color: #ffffff;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 4px;
            background-color: #6200ea;
            color: #ffffff;
            cursor: pointer;
        }
        button:hover {
            background-color: #3700b3;
        }
        p {
            text-align: center;
        }
        a {
            color: #bb86fc;
        }

        /* Media Queries for responsiveness */
        @media (max-width: 480px) {
            form {
                padding: 15px; /* Adjust padding for smaller screens */
                box-shadow: none; /* Remove shadow on very small screens */
            }
        }
    </style>
</head>
<body>
<form method="POST">
    <h2>Login</h2>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="registration.php">Register here</a></p>
</body>
</html>
