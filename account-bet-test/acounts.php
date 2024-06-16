<?php
// Start the session before any output
session_start();
require_once "db-config.php";
$userid = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 10%; /* 10% width */
            min-width: 160px;
            height: 100vh; /* Full height of the viewport */
            background-color: #4B0082;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative; /* Added position */
        }

        .content {
            width: 90%; /* 90% width */
            padding: 20px;
        }

        .sidebar-button {
            display: block;
            width: 100%;
            padding: 12px 0; /* Increased padding */
            text-align: center;
            color: white;
            text-decoration: none;
            background-color: transparent;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 18px; /* Increased font size */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Different font */
        }

        .sidebar-button:hover,
        .sidebar-button.active {
            background-color: #555;
        }

        .menu-container {
            position: absolute;
            top: 10px;
            right: 10px; /* Add space at the right corner */
            display: inline-block;
            font-size: 24px;
            cursor: pointer;
        }

        .dropdown {
            position: relative;
            display: inline-block; /* Keep the dropdown inline */
            margin-bottom: 10px; /* Add space below the dropdown */
        }

        .menu-icon {
            color: white; /* Change to white */
            font-size: 24px; /* Adjust the size as needed */
            margin-bottom: 10px; /* Add space below the icon */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            border-radius: 5px;
            right: 0; /* Position to the right */
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        /* Style for the user box */
        .user-box {
            display: flex;
            flex-direction: column; /* Display items in a column */
            background-color: #fff;
            padding: 20px; /* Increased padding */
            border-radius: 10px;
            margin-bottom: 20px;
            align-items: left; /* Center items horizontally */
        }

        /* Style for the user image */
        .user-image {
            width: 120px; /* Increased size */
            height: 120px; /* Increased size */
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px; /* Add space below the image */
        }

        /* Style for the user details */
        .user-details {
            display: flex;
            flex-direction: column;
            align-items: left; /* Center items horizontally */
        }

        /* Style for the labels */
        .label {
            font-size: 14px;
            color: #999;
        }

        /* Style for the username */
        .username {
            font-size: 24px; /* Increased size */
            color: #4B0082;
            margin-bottom: 10px; /* Add space below the username */
        }

        /* Style for the email */
        .email {
            font-size: 18px; /* Increased size */
            color: #555;
        }

        /* Rest of your styles */
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="menu-container">
            <div class="dropdown">
                <div class="menu-icon">&#9776;</div>
                <div class="dropdown-content">
                    <a href="#">Home</a>
                    <a href="#">About</a>
                    <a href="#">Services</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </div><br><br>

        <a class="sidebar-button" href="index.html">Current Bets</a>
        <a class="sidebar-button" href="bet-history.php">History</a>
        <a class="sidebar-button" href="bet-wallet.php">Wallet</a>
        <a class="sidebar-button" href="bet-notification.php">Notification</a>
    </div>

    <div class="content">
        <?php
            // Retrieve the user's username, image URL, and email from the session
            $sql = "SELECT email FROM users WHERE user_id = $userid";

            $result = mysqli_query($conn, $sql);
            
            $row = mysqli_fetch_assoc($result);
            
            $userEmail = $row['email'];

            $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
            $userImage = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : 'images/mark.jpg';
            $userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com';
        ?>
        <div class="user-box">
            <img class="user-image" src="<?php echo $userImage; ?>" alt="User Image">
            <div class="user-details">
                <div class="label">Username</div>
                <div class="username"><?php echo $username; ?></div>
                <div class="label">Email</div>
                <div class="email"><?php echo $userEmail; ?></div>
            </div>
        </div>

        <!-- Rest of your content -->
    </div>
</body>
</html>
