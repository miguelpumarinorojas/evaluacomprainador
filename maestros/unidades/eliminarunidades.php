<?php

include("../../inc/connection.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idUnidad = (int) $_GET['id'];

    $stmt = $conn->prepare("UPDATE unidades SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $idUnidad);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;