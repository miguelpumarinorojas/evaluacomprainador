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
    $sql = "SELECT  t3.descripcion categorias,		
                    SUM(t1.total_por_producto) total_mensual
            FROM lista_compras t1 	inner join productos t2 on t1.producto = t2.id
                                    inner join categorias t3 on t3.id = t2.categoria
            GROUP BY t3.descripcion
            ORDER BY total_mensual desc";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch_all(MYSQLI_ASSOC);

    echo json_encode($result);
} catch (mysqli_sql_exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
