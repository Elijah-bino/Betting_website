<?php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["txt"];
    $email = $_POST["email"];
    $password = $_POST["pswd"];

    // Set your Coinbase Commerce API key here
    $api_key = "3c5bcadb-9836-400e-b77e-fded1f368159";

    // cURL Request to Coinbase Commerce API
    $curl = curl_init();

    $data = array(
        "name" => "User Wallet",
        "description" => "User Bitcoin Wallet",
        "pricing_type" => "fixed_price",
        "local_price" => array(
            "amount" => "0.10",
            "currency" => "USD"
        ),
        // Other parameters as needed
    );

    $json_data = json_encode($data);

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
            'X-CC-Api-Key: ' . $api_key  // Include your API key here
        ),
        CURLOPT_POSTFIELDS => $json_data
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response_data = json_decode($response, true);

    if (isset($response_data['data']['addresses']['bitcoin'])) {
        $bitcoin_address = $response_data['data']['addresses']['bitcoin'];

        // Insert user data into the database
        $sql = "INSERT INTO users (username, email, password, address) VALUES ('$username', '$email', '$password', '$bitcoin_address')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Address creation failed";
    }

    $conn->close();
}
?>
