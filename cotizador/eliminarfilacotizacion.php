<?php

include("../inc/connection.php");

    $mes_compra = $_POST['mes_compra'] ?? '';
    $supermercado = $_POST['supermercado'] ?? '';

if (isset($_POST['btnEliminar'])) {

    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM cotizador_mensual WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php?mes_compra=$mes_compra&supermercado=$supermercado");
exit;
