<?php

include("../inc/connection.php");

if (isset($_POST['btnEliminar'])) {

    $mesCompra = $_POST['mes_compra'];
    $supermercado = $_POST['supermercado'];

    $stmt = $conn->prepare("DELETE FROM cotizador_mensual WHERE mes_compra = ? AND supermercado = ?");
    $stmt->bind_param("si", $mesCompra, $supermercado);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;