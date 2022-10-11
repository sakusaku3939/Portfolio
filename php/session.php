<?php
session_start();

function validate_token($token): bool
{
    return $token === $_SESSION['token'];
}

function generate_token(): string
{
    $token = hash('sha256', uniqid(rand(), true));
    $_SESSION['token'] = $token;
    return $token;
}