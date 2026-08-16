<?php

include("../../inc/connection.php");

if (isset($_POST['btnModificar'])) {

    $descripcion = $_POST['DescripcionProducto'];
    $categoria = $_POST['CategoriaProducto'];
    $idProducto = $_POST['id'];

    // echo "Descripción: " . $descripcion . "<br>";
    // echo "Categoría: " . $categoria . "<br>";
    // echo "ID del Producto: " . $idProducto . "<br>";

    $stmt = $conn->prepare("UPDATE productos SET descripcion = ?, categoria = ? WHERE id = ?");
    $stmt->bind_param("sii", $descripcion, $categoria, $idProducto);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: index.php");
exit;
