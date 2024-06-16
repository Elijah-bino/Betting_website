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

        /* Style for the box containing the table */
        .table-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* Style for the table */
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        .styled-table th, .styled-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .styled-table th {
            background-color: #f2f2f2;
            font-size: 18px;
        }

        .balance-box {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px #ccc; 
        }

        .balance {
        font-size: 1.2em;
        }

        .withdraw-btn {
        background: blue; 
        color: #fff;
        padding: 10px;
        border-radius: 20px;
        cursor: pointer;
        }

        .notice-box {
        background: #eee;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px #ccc;
        margin-top: 20px; 
        }

        .notice-box p {
        line-height: 1.6;
        }

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
            $sql = "SELECT account_balance FROM users WHERE user_id = $userid";

            $result = mysqli_query($conn, $sql);
            
            $row = mysqli_fetch_assoc($result);
            
            $accountBalance = $row['account_balance'];

            
        ?>

        <div class="balance-box">

        <div class="balance">
        <p>Account Balance</p>
        <p><?php echo $accountBalance; ?></p> 
        </div>

        <button class="withdraw-btn">Withdraw</button>

        </div>

        

    

        <div class="notice-box">

        <p>Notice:</p>

        <p>If you win the bet placed, the reward will be added to your account balance in 2-3 business days which you will be able to withdraw.</p>

        </div>

        
    </div>

    <!-- Rest of your content -->



    

    
    




</body>
</html>
