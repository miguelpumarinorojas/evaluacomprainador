<?php

include("../inc/connection.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idSupermercado = (int) $_GET['id'];

    $stmt = $conn->prepare("UPDATE supermercados SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $idSupermercado);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;