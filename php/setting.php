<?php
// メールホスト名
const MAIL_HOST = 'smtp.gmail.com';

// メールユーザー名・アカウント名
define('MAIL_USERNAME', getenv('MAIL_USERNAME'));

// メールパスワード
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD'));

// SMTPプロトコル(sslまたはtls)
const MAIL_ENCRPT = 'tls';

// 送信ポート(ssl:465, tls:587)
const SMTP_PORT = 587;

// メールアドレス
define('MAIL_FROM', getenv('MAIL_USERNAME'));

// 表示名
const MAIL_FROM_NAME = 'メールフォーム';

// メールタイトル
const MAIL_SUBJECT = 'Portfolioからメールが来ています！';
