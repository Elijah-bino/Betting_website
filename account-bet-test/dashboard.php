<?php
session_start();
require_once "db-config.php"; // Include your database configuration file

// Get the logged-in user's ID from the session
$userId = $_SESSION['user_id'] ?? 0; // Replace with your actual session variable
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$userImage = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : 'images/mark.jpg';
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com';

// Fetch the user's name from the database
$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    
} else {
    $username = "Unknown"; // Default name if user not found
}

$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<style>
  /* Include your CSS styles here */
  body {
    margin: 0;
    font-family: Arial, sans-serif;
  }

  .dashboard-container {
    display: flex;
    height: 100vh;
  }

  .sidebar {
    width: 20%;
    background-color: #333;
    color: white;
    padding: 20px;
  }

  .user-info {
    font-size: 1.5rem;
    margin-bottom: 20px;
  }

  .sidebar ul {
    list-style-type: none;
    padding: 0;
  }

  .sidebar li {
    margin-bottom: 10px;
    cursor: pointer;
  }

  .content {
    flex: 1;
    padding: 20px;
    background-color: #f2f2f2;
    overflow: auto;
  }

  .content-box {
    background-color: white;
    padding: 20px;
  }

  .dashboard-navigation {
    list-style: none;
    padding: 0;
  }

  .dashboard-navigation li {
    cursor: pointer;
    padding: 10px;
    border-bottom: 1px solid #ccc;
  }

  .dashboard-content {
    padding: 20px;
  }

  .user-box {
            display: flex;
            flex-direction: column; /* Display items in a column */
            background-color: #fff;
            padding: 20px; /* Increased padding */
            border-radius: 10px;
            margin-bottom: 20px;
            align-items: left; /* Center items horizontally */
          }

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

</style>
</head>
<body>
  <div class="dashboard-container">
    <div class="sidebar">
      <div class="user-info">
        <?php echo $username; ?>
      </div>
      <ul class="dashboard-navigation">
        <li onclick="showContent('Profile')">Profile</li>
        <li onclick="showContent('Account Balance')">Account Balance</li>
        <li onclick="showContent('Place Bets')">Current Bets</li>
        <li onclick="showContent('Bet History')">Bet History</li>
        <li onclick="showContent('Notifications')">Notifications</li>
        <li onclick="showContent('Logout')">Logout</li>
      </ul>
    </div>
    <div class="content">
      <div id="contentContainer" class="content-box">


      <div class="user-box">
      <img class="user-image" src="<?php echo $userImage; ?>" alt="User Image">
            <div class="user-details">
                <div class="label">Username</div>
                <div class="username"><?php echo $username; ?></div>
                <div class="label">Email</div>
                <div class="email"><?php echo $userEmail; ?></div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function showContent(option) {
        var contentContainer = document.getElementById('contentContainer');

        if (option === 'Affiliate Program') {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'affiliate.php', true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    contentContainer.innerHTML = xhr.responseText;
                }
            };

            xhr.send();
        } else {
            contentContainer.innerHTML = `You selected: ${option}`;
        }
    }
  </script>
</body>
</html>
