<?php

include("../inc/connection.php");
/**
 * Reporte de precios por supermercado
 * Ejecuta sp_reporte_precios_por_super(mes_compra) y muestra el resultado
 * en una tabla HTML. Las columnas de supermercados son dinámicas, así que
 * la tabla se arma leyendo las columnas que realmente devuelve la consulta
 * (no se hardcodean nombres de supermercados).
 */

// ==== Configuración de conexión ====
// Ajusta estos datos a tu entorno (cPanel: normalmente host = 'localhost')
// $db_host = 'localhost';
// $db_name = 'nombre_de_tu_base';
// $db_user = 'usuario_mysql';
// $db_pass = 'password_mysql';

// ==== Parámetro: mes de compra ====
// Viene por GET (?mes=2026-08) o se usa el mes actual por defecto
$mes_compra = isset($_GET['mes']) ? $_GET['mes'] : date('Y-m');

// Validación simple del formato YYYY-MM para evitar valores raros
if (!preg_match('/^\d{4}-\d{2}$/', $mes_compra)) {
    die('Formato de mes inválido. Use YYYY-MM, ej: 2026-08');
}

// ==== Conexión ====
// $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_errno) {
    die('Error de conexión: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

// ==== Ejecutar el procedimiento almacenado ====
$stmt = $conn->prepare('CALL sp_reporte_precios_por_super(?)');
$stmt->bind_param('s', $mes_compra);
$stmt->execute();

$result = $stmt->get_result();

if (!$result) {
    die('Error al ejecutar el procedimiento: ' . $conn->error);
}

// ==== Obtener filas y nombres de columnas dinámicamente ====
$rows = $result->fetch_all(MYSQLI_ASSOC);

var_dump($rows);
die();

$fields = $result->fetch_fields();

$columnas = [];
foreach ($fields as $field) {
    $columnas[] = $field->name;
}

// Columnas fijas que no son supermercados (el resto son precios por super)
$columnas_fijas = ['id_producto', 'descripcion_producto', 'categoria_icono', 'descripcion_categoria'];
$columnas_supermercados = array_diff($columnas, $columnas_fijas);

// Cerrar statement y liberar resultados adicionales (necesario tras CALL)
$stmt->close();
while ($conn->more_results() && $conn->next_result()) {
    // drena resultados extra que deja el CALL a procedimientos
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de precios por supermercado - <?php echo htmlspecialchars($mes_compra); ?></title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1 { font-size: 20px; }
    form { margin-bottom: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: left; }
    th { background-color: #f2f2f2; }
    td.precio { text-align: right; }
    tr:nth-child(even) { background-color: #fafafa; }
    .sin-precio { color: #999; }
</style>
</head>
<body>

<h1>Reporte de precios por supermercado — <?php echo htmlspecialchars($mes_compra); ?></h1>

<form method="get">
    <label>Mes de compra (YYYY-MM):
        <input type="text" name="mes" value="<?php echo htmlspecialchars($mes_compra); ?>">
    </label>
    <button type="submit">Consultar</button>
</form>

<?php if (empty($rows)): ?>
    <p>No se encontraron cotizaciones para el mes <?php echo htmlspecialchars($mes_compra); ?>.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Producto</th>
                <?php foreach ($columnas_supermercados as $super): ?>
                    <th><?php echo htmlspecialchars($super); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['categoria_icono'] . ' ' . $row['descripcion_categoria']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion_producto']); ?></td>
                    <?php foreach ($columnas_supermercados as $super): ?>
                        <td class="precio">
                            <?php
                                if ($row[$super] !== null) {
                                    echo '$' . number_format((float)$row[$super], 0, ',', '.');
                                } else {
                                    echo '<span class="sin-precio">—</span>';
                                }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>