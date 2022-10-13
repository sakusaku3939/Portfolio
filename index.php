<?php
switch (parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
    case '/index.php':
    case '/Portfolio/':
    case '/Portfolio/index.php':
        require 'main.php';
        break;
    case '/php/send_form':
        require 'php/send_form.php';
        break;
    default:
        http_response_code(404);
        exit('<h1 style="text-align: center">404 Not Found</h1>');
}