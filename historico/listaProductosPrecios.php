<?php

include("../inc/connection.php");
include("../inc/funciones.php");

$producto  = $_POST['PRODUCTO'];
$supermercado = $_POST['SUPERMERCADO'];
$fecha = $_POST['FECHA_INICIO'];

if ($producto === '') { ?>
    <table class="table table-striped table-hover table-sm table-responsive">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Marca</th>
                <th>Supermercado</th>
                <th>Mes</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5">Seleccione un producto para ver su historico de precios</td>
            </tr>
        </tbody>
    </table>
<?php } else { ?>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Marca</th>
                <th>Supermercado</th>
                <th>Mes</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $query = "SELECT 	CONCAT(t2.descripcion,' (',t1.capacidad,' ',t5.descripcion,')') descr_producto,
                                t4.descripcion descr_marca,
                                t3.descripcion descr_supermercado, 
                                t1.mes_compra, 
                                t1.precio
                    FROM cotizador_mensual t1   inner join productos t2 on t1.producto = t2.id
                                                inner join supermercados t3 on t1.supermercado = t3.id
                                                inner join marcas t4 on t1.marca = t4.id
                                                inner join unidades t5 on t5.id = t1.um
                    WHERE   (t1.producto = '$producto') and 
                            (t1.supermercado = '$supermercado' or '$supermercado' = '') and 
                            (t1.mes_compra = '$fecha' or '$fecha' = '')
                    ORDER BY t1.precio
                    LIMIT 10";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) == 0) { ?>
                <tr>
                    <td colspan="5">No se encontraron registros para este producto</td>
                </tr>
            <?php }

            if (!$result) {
                die("Error en la consulta: " . mysqli_error($conn));
            }

            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['descr_producto']; ?></td>
                    <td><?php echo $row['descr_marca']; ?></td>
                    <td><?php echo $row['descr_supermercado']; ?></td>
                    <td><?php echo formatoMesAño($row['mes_compra']); ?></td>
                    <td><?php echo formatoMonedaCLP($row['precio']); ?></td>
                </tr>
        <?php }
        } ?>
        </tbody>
    </table>