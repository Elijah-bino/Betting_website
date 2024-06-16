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
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .bet-form-box {
            width: 80%;
            max-width: 400px;
            padding: 10px 20px;
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

        .usdt-address-box {
            margin-top: 20px;
            padding: 10px;
            background-color: #f3f3f3;
            border-radius: 5px;
            text-align: center;
        }

        .usdt-address {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .usdt-qr {
            margin-top: 10px;
        }

        .usdt-details {
            margin-top: 20px;
        }

        .usdt-amount {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
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
    
    function initiatePayment($api_key, $ticketAmount) {
        $json_data = '{
            "price_amount": ' . $ticketAmount . ',
            "price_currency": "usd",
            "pay_currency": "usdtbsc",
            "ipn_callback_url": "https://nowpayments.io",
            "order_id": "RGDBP-21314",
            "order_description": "Apple Macbook Pro 2019 x 1"
        }';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nowpayments.io/v1/payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json_data,
            CURLOPT_HTTPHEADER => array(
                'x-api-key: ' . $api_key,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl); // API response

        $data = json_decode($response, true); // Decode the JSON response

        $payment = $data['payment_id'];

        //echo "1payment_id: {$payment}";

        $address = $data['pay_address'];

        //echo "Send payment to: {$address}";
        

        if (isset($data['pay_address'])) {
            $address = $data['pay_address'];
            return $address; // Return the address
        } else {
            return null;
        }

        curl_close($curl);
    }
    
  
  
    
    if (isset($_POST['purchase'])) {
        // Purchase Tickets button was clicked

        $type = $_GET['type'] ?? '';
        $chosen = $_GET['chosen'] ?? '';

        $ticketAmount = $_POST['ticketAmount'] ?? 0;

        $api_key = 'M1D2BS1-Y3ZMVW0-Q1D8N48-K77ERS8';

        // Initiate payment and display address
        $address = initiatePayment($api_key, $ticketAmount);
    }

    if (isset($_GET['payment_id'])) {
        $payment_id = $_GET['payment_id'];
        
        // Check payment status using NowPayments API
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nowpayments.io/v1/payment/' . $payment_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: ' . $api_key
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);

        // Decode the JSON response
        $payment_data = json_decode($response, true);

        $payment_id = $payment_data['payment_id'];

        //echo "2payment_id: {$payment_id}";

        if ($payment_data['payment_status'] === 'finished') {
            // Payment was successful, send data to server

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

            $sql = "INSERT INTO bet (bet_type, bet_chosen, bet_status,bet_transaction_id, bet_ticket_amount, bet_ticket_amount_value)
                    VALUES ('$type', '$chosen', NULL, '$payment_id', '$ticketAmount', '$ticketAmount * 0.1')";

            if ($conn->query($sql) === TRUE) {
                // Bet inserted successfully
                // You can redirect or display a success message here
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
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
                <p id="totalCost">$<?php echo number_format($_POST['ticketAmount'] * 0.1, 2); ?></p>
            </div>

            <button type="submit" class="purchase-button" name="purchase">Purchase Tickets</button>
        </form>
    </div>

    



    <?php if (isset($address)): ?>
        <div class="usdt-address-box">
            <div class="usdt-address">Send USDT to the following address:</div>
            <div class="usdt-address"><?php echo $address; ?></div>
            <div class="usdt-qr">
                <?php
                    $ticket_cost_usd = $ticketAmount * 0.1; // Total cost of ticket in USD

                    echo '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . $address . '&size=200x200" alt="usdt QR Code">';
                ?>
            </div>
            <div class="bitcoin-details">
                <div class="bitcoin-amount">Amount of usdt to Send: <?php echo $ticket_cost_usd; ?> usdt</div>
            </div>
        </div>
    <?php endif; ?>

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
