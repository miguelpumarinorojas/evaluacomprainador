<?php

include("../inc/connection.php");

    $mes_compra = $_POST['mes_compra'] ?? '';
    $supermercado = $_POST['supermercado'] ?? '';

if (isset($_POST['btnEliminar'])) {

    $id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE cotizador_mensual SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php?mes_compra=$mes_compra&supermercado=$supermercado");
exit;
