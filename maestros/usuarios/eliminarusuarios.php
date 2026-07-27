<?php

include("../../inc/connection.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idUsuario = (int) $_GET['id'];

    // Actualizar el estado del usuario a 0 (inactivo)
    $stmt = $conn->prepare("UPDATE usuarios SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;