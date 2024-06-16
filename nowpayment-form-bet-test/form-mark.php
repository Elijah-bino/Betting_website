<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Add your custom styles for the form here */
        .ticket-form-box {
            width: 80%;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #bebebe;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* ... (your existing styles) ... */

        .bitcoin-address-box {
            margin-top: 20px;
            padding: 10px;
            background-color: #f3f3f3;
            border-radius: 5px;
            text-align: center;
        }

        .bitcoin-address {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .bitcoin-qr {
            margin-top: 10px;
        }

        .bitcoin-details {
            margin-top: 20px;
        }

        .bitcoin-amount {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ticketAmount = $_POST["ticketAmount"];
    
            // Calculate total cost
            $ticketCost = 0.1;
            $totalCost = $ticketAmount * $ticketCost;
    
            // Generate payment address using Coinbase Commerce API
            $api_key = '3c5bcadb-9836-400e-b77e-fded1f368159';
            $data = array(
                "name" => "User Wallet",
                "description" => "User Bitcoin Wallet",
                "pricing_type" => "fixed_price",
                "local_price" => array(
                    "amount" => strval($totalCost),
                    "currency" => "USD"
                )
            );
    
            $data_string = json_encode($data);
    
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.commerce.coinbase.com/charges',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'X-CC-Api-Key: ' . $api_key
                ),
                CURLOPT_POSTFIELDS => $data_string
            ));
    
            $response = curl_exec($curl);

            if ($response === false) {
                echo 'Error: ' . curl_error($curl);
            } else {
                $response_data = json_decode($response, true);
                $bitcoin_address = $response_data['data']['addresses']['bitcoin'];
            }
    
            curl_close($curl);

        
    }
    ?>

    <form class="ticket-form-box" method="POST">
    <div class="form-heading">Bet For Mark</div>
        <label for="ticketAmount">Number of Tickets:</label>
        <input type="number" id="ticketAmount" name="ticketAmount" class="ticket-input" min="1" step="1" required>

        <div class="total-cost">
            Total Cost: <span id="totalCost">$0</span>
        </div>

        <button type="submit" class="bottom-button">Purchase Tickets</button>
    </form>

 

    <?php if (isset($bitcoin_address)): ?>
        <div class="bitcoin-address-box">
            <div class="bitcoin-address">Send Bitcoin to the following address:</div>
            <div class="bitcoin-address"><?php echo $bitcoin_address; ?></div>
            <div class="bitcoin-qr">
                <?php
                    $ticket_cost_usd = $ticketAmount * 0.1; // Total cost of ticket in USD

                    // Fetch current Bitcoin price from API
                    $bitcoin_price_url = 'https://api.coinbase.com/v2/prices/spot?currency=USD';
                    $bitcoin_price_response = file_get_contents($bitcoin_price_url);
                    $bitcoin_price_data = json_decode($bitcoin_price_response, true);
                    $btc_to_usd_exchange_rate = $bitcoin_price_data['data']['amount'];

                    // Calculate Bitcoin amount equivalent to the total cost in USD
                    $bitcoin_equivalent = $ticket_cost_usd / $btc_to_usd_exchange_rate;
                    
                    echo '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . $bitcoin_address . '&size=200x200" alt="Bitcoin QR Code">';
                ?>
            </div>
            <div class="bitcoin-details">
                <div class="bitcoin-amount">Amount of Bitcoin to Send: <?php echo $bitcoin_equivalent; ?> BTC</div>
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