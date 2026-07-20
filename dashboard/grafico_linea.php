<?php
header('Content-Type: application/json; charset=utf-8');

// Conexión a SQL Server con PDO
$serverName = "localhost";
$database   = "evaluacomprainador";
$username   = "root";
$password   = ""; // Cambia esto si tu contraseña es diferente

try {
    $conn = new mysqli($serverName, $username, $password, $database);

    // Ejemplo: obtener nombre y cantidad de votos
    $sql = "SELECT  fecha_cotizacion,
                    SUM(lista_compras.total_por_producto) total_mensual
            FROM lista_compras
            GROUP BY fecha_cotizacion";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch_all(MYSQLI_ASSOC);

    echo json_encode($result);
} catch (mysqli_sql_exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
