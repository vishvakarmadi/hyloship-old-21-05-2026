<?php

$shopifySecret = 'your-shopify-app-secret-here';
if (function_exists('env')) {
    $shopifySecret = env('SHOPIFY_APP_SECRET', $shopifySecret);
} elseif (isset($_ENV['SHOPIFY_APP_SECRET'])) {
    $shopifySecret = $_ENV['SHOPIFY_APP_SECRET'];
}
define('SHOPIFY_APP_SECRET', $shopifySecret);

function verify_webhook($data, $hmac_header) {
    $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
    return hash_equals($hmac_header, $calculated_hmac);
}

$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$data = file_get_contents('php://input');
$verified = verify_webhook($data, $hmac_header);

if ($verified) {
    $response = $data;
} else {
    $response = 'there maybe something wrong';
}

$log = fopen('product.json', 'w') or die('Can\'t open the file');
fwrite($log, $response);
fclose($log);

?>