<?php
// メールホスト名
define('MAIL_HOST', 'smtp.gmail.com');

// メールユーザー名・アカウント名
define('MAIL_USERNAME', getenv('MAIL_USERNAME'));

// メールパスワード
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD'));

// SMTPプロトコル(sslまたはtls)
define('MAIL_ENCRPT', 'tls');

// 送信ポート(ssl:465, tls:587)
define('SMTP_PORT', 587);

// メールアドレス
define('MAIL_FROM', getenv('MAIL_USERNAME'));

// 表示名
define('MAIL_FROM_NAME', 'テスト');

// メールタイトル
define('MAIL_SUBJECT', 'タイトル');