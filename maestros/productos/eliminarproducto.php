<?php

include("../../inc/connection.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idProducto = (int) $_GET['id'];

    $stmt = $conn->prepare("UPDATE productos SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;