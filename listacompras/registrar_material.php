<?php
    include("../../inc/connection.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $CodigoProducto = '0';
        $NombreProducto = $_POST['NombreProducto'];
        $CategoriaProducto = $_POST['CategoriaProducto'];
        $estado = 1; // Estado activo por defecto

        //valida si existe un producto con el mismo nombre
        $checkQuery = "SELECT * FROM productos WHERE descripcion = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $NombreProducto);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            echo "<script>alert('Error: Ya existe un producto con el mismo nombre.'); window.location.href='index.php';</script>";
            exit();
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO productos (codigo, descripcion, categoria,estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $CodigoProducto, $NombreProducto, $CategoriaProducto, $estado);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Producto registrado exitosamente.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error al registrar el producto: " . $stmt->error . "'); window.location.href='index.php';</script>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
