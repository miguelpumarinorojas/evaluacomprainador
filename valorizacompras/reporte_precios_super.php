<?php

include("../inc/connection.php");
/**
 * Reporte de precios por supermercado
 * Ejecuta sp_reporte_precios_por_super(mes_compra) y muestra el resultado
 * en una tabla HTML. Las columnas de supermercados son dinámicas, así que
 * la tabla se arma leyendo las columnas que realmente devuelve la consulta
 * (no se hardcodean nombres de supermercados).
 */

// ==== Parámetro: mes de compra ====
// Viene por POST (?mes=2026-08) o se usa el mes actual por defecto
$mes_compra = isset($_POST['mes']) ? $_POST['mes'] : date('Y-m');

// Validación simple del formato YYYY-MM para evitar valores raros
if (!preg_match('/^\d{4}-\d{2}$/', $mes_compra)) {
    die('Seleccione un mes para visualizar el reporte.');
}

// ==== Conexión ====
// ==== Ruta base donde viven los logos de los supermercados ====
// Los logos están en public_html/maestros/supermercados/, y la columna 'logo'
// en la BD guarda algo como 'logos/tottus.png' relativo a esa carpeta.
// El reporte vive en public_html/valorizacompras/, así que se arma una ruta
// absoluta (desde la raíz del sitio) para que cargue sin importar desde dónde se acceda.
$base_url_logos = '/maestros/supermercados/';


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
$fields = $result->fetch_fields();

$columnas = [];
foreach ($fields as $field) {
    $columnas[] = $field->name;
}

// Columnas fijas que no son supermercados (el resto son precios por super)
$columnas_fijas = ['id_producto', 'descripcion_producto', 'categoria_icono', 'descripcion_categoria', 'descripcion_um', 'Mejor_Precio_Logo', 'Mejor_Precio'];
$columnas_supermercados = array_diff($columnas, $columnas_fijas);

/**
 * Cada celda de supermercado viene como un string compuesto, ej:
 * "Cif: 1660 (3689 por um)"  →  marca: Cif, precio: 1660, precio_por_um: 3689
 * Esta función lo separa en sus partes para poder formatearlo en HTML.
 */
function parsear_celda_precio($valor)
{
    if ($valor === null) {
        return null;
    }
    // Captura: marca (todo antes de los dos puntos), precio, precio por um
    if (preg_match('/^(.*):\s*([\d.,]+)\s*\(\s*([\d.,]+)\s*por um\s*\)\s*$/u', $valor, $m)) {
        return [
            'supermercado'  => trim($m[1]),
            'marca'         => trim($m[1]),
            'precio'        => (float) str_replace(['.', ','], ['', '.'], $m[2]),
            'precio_por_um' => (float) str_replace(['.', ','], ['', '.'], $m[3]),
            'unidad'        => trim($m[4]),
        ];
    }
    // Si no matchea el formato esperado, se devuelve tal cual para no perder el dato
    return ['raw' => $valor];
}

/**
 * Celda de Mejor_Precio, que además trae el nombre del supermercado adelante, ej:
 * "Lider - Nescafe: $12690 ( $63450 por Kilo)"
 */
function parsear_mejor_precio($valor)
{
    if ($valor === null) {
        return null;
    }
    if (preg_match('/^(.*?)\s-\s(.*):\s*\$\s*([\d.,]+)\s*\(\s*\$\s*([\d.,]+)\s*por\s+(.+?)\s*\)\s*$/u', $valor, $m)) {
        return [
            'supermercado'  => trim($m[1]),
            'marca'         => trim($m[2]),
            'precio'        => (float) str_replace(['.', ','], ['', '.'], $m[3]),
            'precio_por_um' => (float) str_replace(['.', ','], ['', '.'], $m[4]),
            'unidad'        => trim($m[5]),
        ];
    }
    return ['raw' => $valor];
}

// Cerrar statement y liberar resultados adicionales (necesario tras CALL)
$stmt->close();
while ($conn->more_results() && $conn->next_result()) {
    // drena resultados extra que deja el CALL a procedimientos
}

$conn->close();
?>
<?php if (empty($rows)): ?>
    <p>No se encontraron cotizaciones para el mes <?php echo htmlspecialchars($mes_compra); ?>.</p>
<?php else: ?>
    <table class="table table-striped table-bordered table-hover table-sm">
        <thead class="table-dark sticky-top">
            <tr>
                <th scope="col">Categoría</th>
                <th scope="col">Producto</th>
                <th scope="col">UM</th>
                <th scope="col" class="table-dark">Mejor Precio</th>
                <?php foreach ($columnas_supermercados as $super): ?>
                    <th scope="col" class="table-primary"><?php echo htmlspecialchars($super); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><span class="material-symbols-outlined align-bottom"><?php echo htmlspecialchars($row['categoria_icono']); ?></span> <?php echo htmlspecialchars($row['descripcion_categoria']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion_producto']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion_um'] ?? ''); ?></td>
                    <td class="precio mejor-precio fw-bold table-warning">
                        <?php
                        $mejor = parsear_mejor_precio($row['Mejor_Precio'] ?? null);
                        if (!empty($row['Mejor_Precio_Logo'])) {
                            // echo '<img src="../maestros/supermercados/' . htmlspecialchars($row['Mejor_Precio_Logo']) . '" alt="' . htmlspecialchars($mejor['supermercado']) . '" height="50">';
                            echo '<img src="' . $base_url_logos . htmlspecialchars($row['Mejor_Precio_Logo']) . '" alt="' . htmlspecialchars($mejor['supermercado']) . '" height="50">';
                        }
                        if ($mejor === null) {
                            echo '<span class="sin-precio">—</span>';
                        } elseif (isset($mejor['raw'])) {
                            echo htmlspecialchars($mejor['raw']);
                        } else {
                            // echo '<div class="supermercado">' . htmlspecialchars($mejor['supermercado']) . '</div>';
                            echo '<div class="marca">' . htmlspecialchars($mejor['marca']) . '</div>';
                            echo '<div class="precio-valor">$' . number_format($mejor['precio'], 0, ',', '.') . '</div>';
                            echo '<div class="precio-um">($' . number_format($mejor['precio_por_um'], 0, ',', '.') . ' /' . htmlspecialchars($mejor['unidad']) . ')</div>';
                        }
                        ?>
                    </td>
                    <?php foreach ($columnas_supermercados as $super): ?>
                        <td class="precio">
                            <?php
                            $datos = parsear_celda_precio($row[$super]);
                            if ($datos === null) {
                                echo '<span class="sin-precio">—</span>';
                            } elseif (isset($datos['raw'])) {
                                // formato inesperado: se muestra tal cual, sin parsear
                                echo htmlspecialchars($datos['raw']);
                            } else {
                                echo '<div class="marca">' . htmlspecialchars($datos['marca']) . '</div>';
                                echo '<div class="precio-valor">$' . number_format($datos['precio'], 0, ',', '.') . '</div>';
                                echo '<div class="precio-um">($' . number_format($datos['precio_por_um'], 0, ',', '.') . ' /' . htmlspecialchars($datos['unidad']) . ')</div>';
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<form method="POST" action="registrarCompra.php" onsubmit="return confirm('¿Está seguro de registrar esta compra?');">
    <!-- <div class="card-footer text-end"> -->
    <div class="d-grid gap-2">
        <?php if ($mes_compra != '' && $result->num_rows > 0) { ?>
            <a href="listar.php?mes_compra=<?php echo $mes_compra; ?>" target="_blank" class="btn btn-outline-danger" name="btnRegistrarCompra">
                <span class="material-icons align-bottom">picture_as_pdf</span> Generar PDF
            </a>
        <?php } else { ?>
            <button target="_blank" class="btn btn-outline-secondary" name="btnRegistrarCompra" disabled>
                <span class="material-icons align-bottom">block</span> Selecciona un mes y año para generar PDF
            </button>
        <?php   } ?>

    </div>
    <!-- </div> -->
</form>