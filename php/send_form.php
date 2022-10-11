<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/setting.php';

use PHPMailer\PHPMailer\PHPMailer;

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
    echo false;
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
        echo true;
    } else {
        echo false;
    }
} catch (\PHPMailer\PHPMailer\Exception $e) {
    echo false;
}