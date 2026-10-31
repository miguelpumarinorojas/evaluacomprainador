<?php

session_start();

$googleConfigPath = __DIR__ . '/google-config.php';

if (!is_file($googleConfigPath)) {
    http_response_code(500);
    exit('Google OAuth no está configurado.');
}

$googleConfig = require $googleConfigPath;
$clientId = $googleConfig['client_id'] ?? '';
$redirectUri = $googleConfig['redirect_uri'] ?? '';

if ($clientId === '' || $redirectUri === '') {
    http_response_code(500);
    exit('Google OAuth no está configurado.');
}

$params = [
    'client_id' => $clientId,
    'redirect_uri' => $redirectUri,
    'response_type' => 'code',
    'scope' => 'email profile',
    'access_type' => 'online',
    'prompt' => 'select_account',
];

header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
exit;