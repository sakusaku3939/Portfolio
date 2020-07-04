<?php
//$name = $_POST['name'];
//$email = $_POST['email'];
//$message = $_POST['message'];
//
//$mail_message .= '名前： '.$name.PHP_EOL;
//$mail_message .= 'メールアドレス： '.$email.PHP_EOL.PHP_EOL;
//$mail_message .= $message;
//
//mb_language('Japanese');
//mb_internal_encoding('UTF-8');
//
//if (mb_send_mail('yuukiyuuki223@gmail.com', 'Portfolioからお問い合わせが来ています', $mail_message, 'From: yuukiyuuki223@gmail.com')) {
//    echo '<div class="center"><span class="dli-check"></span></div><p>メールが送信されました</p><br>';
//} else {
//    echo '<div class="center"><span class="dli-close"></span></div><p>メールの送信に失敗しました</p>';
//}
use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';
require 'setting.php';
// PHPMailerのインスタンス生成
$mail = new PHPMailer();

$mail->isSMTP(); // SMTPを使うようにメーラーを設定する
$mail->SMTPAuth = true;
$mail->Host = MAIL_HOST; // メインのSMTPサーバーを指定する
$mail->Username = MAIL_USERNAME; // SMTPユーザー名
$mail->Password = MAIL_PASSWORD; // SMTPパスワード
$mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、 「SSL」も受け入れます
$mail->Port = SMTP_PORT; // 接続するTCPポート

// メール内容設定
$mail->CharSet = "UTF-8";
$mail->Encoding = "base64";
$mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
$mail->addAddress(getenv('MAIL_USERNAME'), '受信者名'); //受信者を追加する
$mail->Subject = MAIL_SUBJECT; // メールタイトル
$mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
$body = 'メールの中身';

$mail->Body = $body; // メール本文
// メール送信の実行
if (!$mail->send()) {
    echo 'メッセージは送られませんでした！';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo '送信完了！';
}
?>

<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
<style>
    body::-webkit-scrollbar {
        display: none;
    }

    .center {
        text-align: center;
        margin: 45% 0 20%;
    }

    p {
        text-align: center;
        font-family: 'Noto Sans JP', sans-serif;
    }

    .dli-check {
        display: inline-block;
        vertical-align: middle;
        color: #8BC34A;
        line-height: 1;
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
        vertical-align: middle;
        color: #f44336;
        line-height: 1;
        width: 80px;
        height: 10px;
        background: currentColor;
        border-radius: 0.1em;
        position: relative;
        transform: rotate(45deg);
        margin: 15px 0;
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
