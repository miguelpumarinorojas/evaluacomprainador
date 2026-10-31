<?php

session_start();

require_once __DIR__ . '/../inc/connection.php';

$googleConfigPath = __DIR__ . '/google-config.php';

if (!is_file($googleConfigPath)) {
    http_response_code(500);
    exit('Google OAuth no está configurado.');
}

$googleConfig = require $googleConfigPath;
$clientId = $googleConfig['client_id'] ?? '';
$clientSecret = $googleConfig['client_secret'] ?? '';
$redirectUri = $googleConfig['redirect_uri'] ?? '';

if ($clientId === '' || $clientSecret === '' || $redirectUri === '') {
    http_response_code(500);
    exit('Google OAuth no está configurado.');
}

if (!isset($_GET['code'])) {
    header('Location: login.php?status=google_error');
    exit;
}

$code = $_GET['code'];

$tokenData = [
    'code' => $code,
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code',
];

$ch = curl_init('https://oauth2.googleapis.com/token');

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($tokenData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
]);

$tokenResponse = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if ($tokenResponse === false || $curlError !== '') {
    header('Location: login.php?status=google_error');
    exit;
}

$token = json_decode($tokenResponse, true);

if (!isset($token['access_token'])) {
    header('Location: login.php?status=google_error');
    exit;
}

/*
 * Desde aquí comienza la parte:
 * "Ahora consulta los datos del usuario"
 */

$ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');

curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token['access_token'],
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
]);

$userResponse = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if ($userResponse === false || $curlError !== '') {
    header('Location: login.php?status=google_error');
    exit;
}

$googleUser = json_decode($userResponse, true);

if (
    !isset($googleUser['sub']) ||
    !isset($googleUser['email']) ||
    empty($googleUser['email_verified'])
) {
    header('Location: login.php?status=google_error');
    exit;
}

$googleId = $googleUser['sub'];
$email = strtolower(trim($googleUser['email']));
$nombre = $googleUser['name'] ?? $email;

/*
 * Verificar que el correo exista y que el usuario esté activo.
 */

$stmt = $conn->prepare(
    "SELECT nombre, email, perfil, estado
     FROM usuarios
     WHERE email = ?
     LIMIT 1"
);

$stmt->bind_param('s', $email);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (!$user || (int) $user['estado'] !== 1) {
    header('Location: login.php?status=google_user_not_allowed');
    exit;
}

/*
 * Crear la misma sesión que utiliza el login tradicional.
 */

$mes_compra = date('Y-m');

$_SESSION['loggedin'] = true;
$_SESSION['start'] = time();
$_SESSION['expire'] = $_SESSION['start'] + (100 * 120);
$_SESSION['nombre'] = $user['nombre'];
$_SESSION['email'] = $user['email'];
$_SESSION['perfil'] = $user['perfil'];
$_SESSION['timeout'] = time();

if ((int) $user['perfil'] === 3) {
    header("Location: ../listacompras/index.php?mes_compra=$mes_compra");
} else {
    header('Location: ../index.php');
}

exit;