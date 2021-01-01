<?php
use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';
require 'setting.php';

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

// メール内容設定
$mail->CharSet = "UTF-8";
$mail->Encoding = "base64";
try {
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addAddress(MAIL_USERNAME, 'Me');
} catch (\PHPMailer\PHPMailer\Exception $e) {
    echo '<div class="center"><span class="dli-close"></span><p>メールの送信に失敗しました</p></div>';
}
$mail->Subject = MAIL_SUBJECT;
$mail->isHTML(false);

$body = '';
$body .= '名前： '.$name.PHP_EOL;
$body .= 'メールアドレス： '.$email.PHP_EOL.PHP_EOL;
$body .= $message;
$mail->Body = $body;

// メール送信の実行
if (!$mail->send()) {
    echo '<div class="center"><span class="dli-close"></span><p>メールの送信に失敗しました</p></div>';
} else {
    echo '<div class="center"><span class="dli-check"></span><p>メールが送信されました</p></div>';
}
?>

<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
<style>
    body::-webkit-scrollbar {
        display: none;
    }

    .center {
        position: absolute;
        width: 80%;
        height: 100px;
        top: 50%;
        left: 50%;
        transform: translateY(-50%) translateX(-50%);
        -webkit- transform: translateY(-50%) translateX(-50%);
        text-align: center;
    }

    p {
        margin-top: 40px;
        font-family: 'Noto Sans JP', sans-serif;
    }

    .dli-check {
        display: inline-block;
        color: #8BC34A;
        width: 80px;
        height: 40px;
        border: 0.5em solid currentColor;
        border-top: 0;
        border-right: 0;
        box-sizing: border-box;
        transform: translateY(-25%) rotate(-45deg);
    }

    .dli-close {
        display: inline-block;
        color: #f44336;
        width: 100px;
        height: 10px;
        background: currentColor;
        border-radius: 0.1em;
        position: relative;
        transform: rotate(45deg);
        margin: 20px 0;
    }

    .dli-close::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: inherit;
        border-radius: inherit;
        transform: rotate(90deg);
    }
</style>
