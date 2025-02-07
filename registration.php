<?php
include 'db.php';
session_start();
function is_strong_password($password) {
    return preg_match('/[A-Z]/', $password) &&          
           preg_match('/[a-z]/', $password) &&         
           preg_match('/[0-9]/', $password) &&         
           preg_match('/[\W_]/', $password) &&         
           strlen($password) >= 8;                    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: #bb86fc;'>Invalid email format.</p>";
    } elseif (!is_strong_password($password)) {
        echo "<p style='color: #bb86fc;'>Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.</p>";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<p style='color: #bb86fc;'>Email already exists.</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password);
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Registration successful!</p>";
            } else {
                echo "<p style='color: #bb86fc;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
            }
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
    <title>Register</title>
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
            width: 90%; 
            max-width: 400px;
        }
        input[type="text"],
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

        @media (max-width: 480px) {
            form {
                padding: 15px;
                box-shadow: none; 
            }
        }
    </style>
</head>
<body>
<form method="POST">
    <h2>Register</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
