<?php

header('Content-Type: application/json; charset=utf-8');

include("../inc/connection.php");

function responder($success, $message)
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
    ]);
    exit;
}

$mesCompra = isset($_POST['mes_compra']) ? trim($_POST['mes_compra']) : '';
$producto = isset($_POST['producto']) ? (int) $_POST['producto'] : 0;
$supermercado = isset($_POST['supermercado']) ? (int) $_POST['supermercado'] : 0;
$marca = isset($_POST['marca']) ? (int) $_POST['marca'] : 0;
$um = isset($_POST['um']) ? (int) $_POST['um'] : 0;
$capacidad = isset($_POST['capacidad']) ? (float) str_replace(',', '.', $_POST['capacidad']) : 0;
$precio = isset($_POST['precio']) ? (float) $_POST['precio'] : 0;
$precioPorUm = isset($_POST['precio_por_um']) ? (float) $_POST['precio_por_um'] : 0;

if ($mesCompra === '' || $producto <= 0 || $supermercado <= 0) {
    responder(false, 'Faltan datos clave para actualizar la cotización.');
}

$stmt = $conn->prepare(
    "UPDATE cotizador_mensual
     SET marca = ?, um = ?, capacidad = ?, precio = ?, precio_por_um = ?
     WHERE mes_compra = ? AND producto = ? AND supermercado = ?"
);

if (!$stmt) {
    responder(false, 'No se pudo preparar la actualización.');
}

$stmt->bind_param('iidddsii', $marca, $um, $capacidad, $precio, $precioPorUm, $mesCompra, $producto, $supermercado);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    responder(false, 'No se pudo actualizar la cotización.');
}

$stmt->close();
$conn->close();

responder(true, 'Cotización actualizada correctamente.');