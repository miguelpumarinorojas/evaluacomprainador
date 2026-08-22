<?php
header('Content-Type: application/json; charset=utf-8');

// Recibir parámetro por POST
$producto = isset($_POST['producto']) ? trim($_POST['producto']) : "";

include("../inc/connection.php");

// Conexión a MySQL con mysqli
// $serverName = "localhost";
// $database   = "evaluacomprainador";
// $username   = "root";
// $password   = ""; // Cambia esto si tu contraseña es diferente

try {
    // $conn = new mysqli($serverName, $username, $password, $database);

    // Construir SQL base
    $sql = "SELECT 	t3.descripcion descr_supermercado, 
                    AVG(t1.precio) precio_promedio
                    FROM cotizador_mensual t1   inner join productos t2 on t1.producto = t2.id
                                                inner join supermercados t3 on t1.supermercado = t3.id
                                                inner join marcas t4 on t1.marca = t4.id
                                                inner join unidades t5 on t5.id = t1.um";

    // Agregar filtro solo si $producto no está vacío
    if (!empty($producto)) {
        $sql .= " WHERE t1.producto = '" . $conn->real_escape_string($producto) . "'";
    }

    $sql .= " GROUP BY t3.descripcion
              ORDER BY precio_promedio DESC";

    $stmt = $conn->query($sql);
    $result = $stmt->fetch_all(MYSQLI_ASSOC);

    echo json_encode($result);
} catch (mysqli_sql_exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
