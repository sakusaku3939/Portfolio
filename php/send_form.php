<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/setting.php';
require_once dirname(__FILE__) . '/session.php';

$token = filter_input(INPUT_POST, 'token');
if (!validate_token($token)) {
    echo "error: Invalid Token";
    exit;
}

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$body = '名前： ' . $name . PHP_EOL;
$body .= 'メールアドレス： ' . $email . PHP_EOL . PHP_EOL;
$body .= $message;

$message = ['text' => $body];

$ch = curl_init();
$options = [
    CURLOPT_URL => getenv('WEBHOOK_URL'),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'payload' => json_encode($message)
    ])
];

curl_setopt_array($ch, $options);
curl_exec($ch);

if (curl_errno($ch)) {
    echo "error: " . curl_error($ch);
}

curl_close($ch);

echo "success";