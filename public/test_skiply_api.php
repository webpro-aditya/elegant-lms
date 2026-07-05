<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$client_id = getPaymentEnv('SKIPLY_CLIENT_ID');
$client_secret = getPaymentEnv('SKIPLY_CLIENT_SECRET');
$environment = getPaymentEnv('SKIPLY_ENVIRONMENT');
$base_url = $environment == 'Production' ? 'https://skiply.ae' : 'https://qa.skiply.ae';

// Get token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base_url . "/skiply-userprofile/oauth/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "Grant-Type=client_credentials");
curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$headers = array("Content-Type: application/x-www-form-urlencoded");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
curl_close($ch);

$token_response = json_decode($result, true);
$access_token = $token_response['access_token'] ?? null;

if (!$access_token) {
    die("Failed to get token\n");
}

echo "Got token: " . substr($access_token, 0, 10) . "...\n";

$orderId = "ORDER_1783261623_25"; // From user's logs
$endpoints = [
    "/skiply-payment/checkout/" . $orderId . "/status",
    "/skiply-payment/checkout/status?orderId=" . $orderId,
    "/skiply-payment/checkout/order/" . $orderId . "/status",
];

foreach ($endpoints as $ep) {
    echo "Trying: " . $ep . "\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url . $ep);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $headers = array();
    $headers[] = "Authorization: Bearer " . $access_token;
    $headers[] = "Accept: application/json";
    $headers[] = "Content-Type: application/json";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $res = curl_exec($ch);
    $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Code: " . $info . "\n";
    echo "Response: " . substr($res, 0, 200) . "\n\n";
}
