<?php
    include("../inc/connection.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $CodigoProducto = '0';
        $NombreProducto = trim($_POST['NombreProducto'] ?? '');
        $CategoriaProducto = $_POST['CategoriaProducto'] ?? '';
        $estado = 1; // Estado activo por defecto

        error_log('[productos] Intento de registro desde registrar_material.php: descripcion=' . $NombreProducto . ', categoria=' . $CategoriaProducto);

        if ($NombreProducto === '' || $CategoriaProducto === '') {
            error_log('[productos] Datos incompletos en registrar_material.php');
            echo "<script>alert('Debe completar la descripción y la categoría del producto.'); window.location.href='index.php';</script>";
            exit();
        }

        //valida si existe un producto con el mismo nombre
        $checkQuery = "SELECT * FROM productos WHERE descripcion = ?";
        $checkStmt = $conn->prepare($checkQuery);
        if (!$checkStmt) {
            error_log('[productos] Error al preparar validación de producto: ' . $conn->error);
            echo "<script>alert('No se pudo validar el producto.'); window.location.href='index.php';</script>";
            exit();
        }
        $checkStmt->bind_param("s", $NombreProducto);
        if (!$checkStmt->execute()) {
            error_log('[productos] Error al ejecutar validación de producto: ' . $checkStmt->error);
            $checkStmt->close();
            echo "<script>alert('No se pudo validar el producto.'); window.location.href='index.php';</script>";
            exit();
        }
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            $checkStmt->close();
            echo "<script>alert('Error: Ya existe un producto con el mismo nombre.'); window.location.href='index.php';</script>";
            exit();
        }
        $checkStmt->close();

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO productos (codigo, descripcion, categoria,estado) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            error_log('[productos] Error al preparar INSERT desde registrar_material.php: ' . $conn->error);
            echo "<script>alert('No se pudo preparar el registro del producto.'); window.location.href='index.php';</script>";
            exit();
        }
        $stmt->bind_param("ssii", $CodigoProducto, $NombreProducto, $CategoriaProducto, $estado);

        // Execute the statement
        if ($stmt->execute()) {
            error_log('[productos] INSERT realizado correctamente desde registrar_material.php. ID: ' . $conn->insert_id);
            echo "<script>alert('Producto registrado exitosamente.'); window.location.href='index.php';</script>";
        } else {
            error_log('[productos] Error al ejecutar INSERT desde registrar_material.php: ' . $stmt->error);
            echo "<script>alert('No se pudo registrar el producto.'); window.location.href='index.php';</script>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
