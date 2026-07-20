<?php

include("../inc/connection.php");


if (isset($_POST['btnEliminar'])) {

    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM lista_compras WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;
