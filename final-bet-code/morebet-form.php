<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Your custom styles for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .bet-form-box {
            width: 80%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-heading {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }

        .ticket-input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .total-cost {
            margin-top: 20px;
        }

        .purchase-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .purchase-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php
        session_start(); // Start the session

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect user to login page or handle as appropriate
            // ...
        }

        if (isset($_POST['purchase'])) {
            // Purchase Tickets button was clicked

            $type = $_GET['type'] ?? '';
            $chosen = $_GET['chosen'] ?? '';

            $ticketAmount = $_POST['ticketAmount'] ?? 0;
            $ticketCost = 0.1; // Cost per ticket in USD
            $totalCostUSD = $ticketAmount * $ticketCost;

            // Insert bet details into the database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "megabet";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO bet (bet_type, bet_chosen, bet_status, bet_ticket_amount, bet_ticket_amount_value)
                    VALUES ('$type', '$chosen', NULL, '$ticketAmount', '$totalCostUSD')";

            if ($conn->query($sql) === TRUE) {
                // Bet inserted successfully
                // You can redirect or display a success message here
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
    ?>

    <div class="bet-form-box">
        <div class="form-heading">Bet Details</div>

        <form method="POST">
            <!-- Passing type and chosen values to the form -->
            <input type="hidden" name="type" value="<?php echo $_GET['type'] ?? ''; ?>">
            <input type="hidden" name="chosen" value="<?php echo $_GET['chosen'] ?? ''; ?>">

            <label class="input-label">Type:</label>
            <p><?php echo $_GET['type'] ?? ''; ?></p>

            <label class="input-label">Chosen:</label>
            <p><?php echo $_GET['chosen'] ?? ''; ?></p>

            <label class="input-label">Number of Tickets:</label>
            <input type="number" id="ticketAmount" class="ticket-input" name="ticketAmount" min="1" step="1" required>

            <div class="total-cost">
                <label class="input-label">Total Cost (USD):</label>
                <p id="totalCost">$<?php echo number_format($totalCostUSD, 2); ?></p>
            </div>

            <button type="submit" class="purchase-button" name="purchase">Purchase Tickets</button>
        </form>
    </div>

    <script>
        const ticketAmountInput = document.getElementById('ticketAmount');
        const totalCostElement = document.getElementById('totalCost');

        ticketAmountInput.addEventListener('input', updateTotalCost);
        ticketAmountInput.addEventListener('change', updateTotalCost);

        function updateTotalCost() {
            const ticketAmount = ticketAmountInput.valueAsNumber;
            const ticketCost = 0.1;
            const totalCost = ticketAmount * ticketCost;
            totalCostElement.textContent = `$${totalCost.toFixed(2)}`;
        }
    </script>
</body>
</html>
