<?php
session_start();
require_once "db-config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["txt"];
    $email = $_POST["email"];
    $password = $_POST["pswd"]; // Store the password as plain text

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
}
    // After successful insertion
if ($conn->query($sql) === TRUE) {
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['username'] = $username; // Store the username in the session
    $_SESSION['email'] = $email;
    header("Location: login_form.php?signup=success"); // Redirect with success parameter
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>