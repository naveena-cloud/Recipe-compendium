<?php
session_start();

// Get role from query parameter
$role = $_GET['role'] ?? 'user'; // default to 'user' if not provided

$servername = "localhost";
$db_username = "root";
$db_password = "";
$database = "recipe_manager";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php

// Handle login
$message = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "1234") {
        $_SESSION['role'] = "admin";
        $message = "Admin login successful!";
    } elseif ($username === "user" && $password === "5678") {
        $_SESSION['role'] = "user";
        $message = "User login successful!";
    } else {
        $message = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recipe Compendium - Login</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Full-screen background */
        .bg {
            background-image: url('id.jpg'); /* your background image */
            height: 100%;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            position: relative;
        }

        /* Overlay for readability */
        .overlay {
            position: absolute;
            height: 100%;
            width: 100%;
            background-color: rgba(0,0,0,0.5);
            top: 0;
            left: 0;
        }

        /* Centered login box */
        .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(255,255,255,0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
            width: 300px;
        }

        .centered h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type=text], input[type=password] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            padding: 12px 25px;
            font-size: 18px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-btn {
            background-color: #4CAF50;
            color: white;
        }

        .login-btn:hover {
            background-color: #45a049;
        }

        .compendium-btn {
            margin-top: 20px;
            background-color: #2196F3;
            color: white;
            width: 100%;
        }

        .compendium-btn:hover {
            background-color: #0b7dda;
        }

        .message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="bg">
    <div class="overlay"></div>
    <div class="centered">
        <h2>Login</h2>

        <?php if($message != "") echo "<div class='message'>$message</div>"; ?>

        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login" class="login-btn">Login</button>
        </form>

        <?php if(isset($_SESSION['role'])): ?>
            <!-- Show button only after successful login -->
            <button class="compendium-btn" onclick="window.location.href='recipe_food.php?role=<?php echo $_SESSION['role']; ?>'">
                Recipe Compendium
            </button>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
