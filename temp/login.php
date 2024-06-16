<?php
session_start();
require_once "db-config.php";

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: e-r.html"); // Redirect to the logged-in page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["pswd"]; // Plain text password

    // Replace this with your actual check for user login
    $sql = "SELECT user_id, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $storedPassword = $user['password'];

        if ($password === $storedPassword) {
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: e-r.html");
            exit();
        } else {
            echo "Invalid credentials. Please try again.";
        }
    } else {
        echo "Invalid credentials. Please try again.";
    }
}

$conn->close();
?>
