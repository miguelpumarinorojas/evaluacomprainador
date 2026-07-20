<?php

include("../inc/connection.php");
include("../inc/funciones.php");

$filasGeneradas = [];
$fecha_cotizacion = $_POST['FECHA_COTIZACION'];

?>

<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark sticky-top">
        <tr>
            <th width="5">N°</th>
            <th>Producto</th>
            <th>Cat</th>
            <th>Cantidad</th>
            <!-- <th><input type="checkbox" name="seleccionar-todos[]" id="seleccionar-todos"></th> -->
        </tr>
    </thead>
    <tbody>
        <?php

        if (empty($fecha_cotizacion)) { ?>
           <tr>
                <td colspan="4">Seleccione una fecha para mostrar los productos.</td>
            </tr>
       <?php } else {

        $query_1 = "SELECT * FROM lista_compras_mensual WHERE mes_compra = '$fecha_cotizacion'";
        $result_1 = $conn->query($query_1);
        if ($result_1->num_rows > 0) {

            $query = "SELECT 
                            p.id,
                            p.descripcion,
                            c.icono,
                            c.descripcion AS descripcion_categoria,
                            IFNULL(l.cantidad, '') AS cantidad,
                            IFNULL(l.estado, '') AS estado
                        FROM productos p
                        INNER JOIN categorias c ON p.categoria = c.id
                        LEFT JOIN lista_compras_mensual l 
                            ON p.id = l.producto 
                            AND l.mes_compra = '$fecha_cotizacion'
                        WHERE p.estado = 1
                        ORDER BY c.descripcion, p.descripcion;";

        } else {
            $query = "SELECT DISTINCT t1.id, t1.descripcion,t3.icono,t3.descripcion as descripcion_categoria, '' as cantidad, '' as estado
                        FROM productos t1 
                        INNER JOIN categorias t3 on t1.categoria = t3.id
                        WHERE t1.estado = 1
                        ORDER BY t3.descripcion, t1.descripcion";
        }

        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row_ppal = $result->fetch_assoc()) {
                $filasGeneradas[] = $numero;
        ?>
                <tr>
                    <td class="text-center"><?php echo $numero++; ?></td>
                    <td><?php echo $row_ppal['descripcion']; ?></td>
                    <td><span class="material-symbols-outlined"><?php echo $row_ppal['icono']; ?></span></td>
                    <td>
                        <input type="number" class="form-control"  name="cantidades[]" value="<?php echo $row_ppal['cantidad']; ?>" min="0">
                        <input type="hidden" name="productos[]" value="<?php echo $row_ppal['id']; ?>">
                    </td>
                    <!-- <td><input type="checkbox" name="seleccionados[]" id="seleccionado-<?php //echo $row_ppal['id']; ?>" value="<?php //echo $row_ppal['id']; ?>" <?php //echo ($row_ppal['estado'] == 1) ? 'checked' : ''; ?>></td> -->
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4">No se encontraron listas generadas.</td>
            </tr>
        <?php }         }
        $conn->close();
        ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#seleccionar-todos').click(function() {
            var checked = $(this).prop('checked');
            $('input[name="seleccionados[]"]').prop('checked', checked);
        });
    });
</script>