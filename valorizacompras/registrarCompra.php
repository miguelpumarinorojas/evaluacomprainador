<?php

include("../inc/connection.php");

$mensaje = 'No se realizaron cambios.';
$tipo = 'warning';

if (isset($_POST['btnRegistrarCompra'])) {
    $stmt = $conn->prepare("UPDATE lista_compras SET estado = 1 WHERE estado = 0");

    if ($stmt) {
        if ($stmt->execute()) {
            $filasActualizadas = $stmt->affected_rows;
            if ($filasActualizadas > 0) {
                $mensaje = 'La compra fue registrada correctamente. Se actualizaron ' . $filasActualizadas . ' registros.';
                $tipo = 'success notification';
            } else {
                $mensaje = 'No se encontraron registros pendientes para registrar.';
                $tipo = 'info notification';
            }
        } else {
            $mensaje = 'No se pudo actualizar el estado de los registros.';
            $tipo = 'danger notification';
        }

        $stmt->close();
    } else {
        $mensaje = 'No se pudo preparar la actualización.';
        $tipo = 'danger notification';
    }
}

$conn->close();

header('Location: index.php?tipo=' . urlencode($tipo) . '&mensaje=' . urlencode($mensaje));
exit;