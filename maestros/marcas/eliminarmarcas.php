<?php

include("../../inc/connection.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idMarca = (int) $_GET['id'];

    $stmt = $conn->prepare("UPDATE marcas SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $idMarca);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;