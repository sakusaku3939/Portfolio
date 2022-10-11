<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/setting.php';
require_once dirname(__FILE__) . '/session.php';

use PHPMailer\PHPMailer\PHPMailer;

$token = filter_input(INPUT_POST, 'token');
if (!validate_token($token)) error_exit();

$mail = new PHPMailer();

$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Host = MAIL_HOST;
$mail->Username = MAIL_USERNAME;
$mail->Password = MAIL_PASSWORD;
$mail->SMTPSecure = MAIL_ENCRPT;
$mail->Port = SMTP_PORT;

//POSTで受け取り
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

//メール内容設定
$mail->CharSet = "UTF-8";
$mail->Encoding = "base64";
try {
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addAddress(MAIL_USERNAME, 'Me');
} catch (\PHPMailer\PHPMailer\Exception $e) {
    error_exit();
}
$mail->Subject = MAIL_SUBJECT;
$mail->isHTML(false);

$body = '名前： ' . $name . PHP_EOL;
$body .= 'メールアドレス： ' . $email . PHP_EOL . PHP_EOL;
$body .= $message;
$mail->Body = $body;

//メール送信の実行
try {
    if ($mail->send()) {
        echo "success";
    } else {
        error_exit();
    }
} catch (\PHPMailer\PHPMailer\Exception $e) {
    error_exit();
}

function error_exit()
{
    echo "error";
    exit;
}