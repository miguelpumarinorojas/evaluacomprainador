<?php

include("../inc/connection.php");

function formatoMonedaCLP($valor)
{
    return '$ ' . number_format((float) $valor, 0, ',', '.');
}

function formatoFechaDMY($fecha)
{
    $fechaObj = DateTime::createFromFormat('Y-m-d', (string) $fecha);
    return $fechaObj instanceof DateTime ? $fechaObj->format('d-m-Y') : $fecha;
}

?>


<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark">
        <tr>
            <th width="50">N°</th>
            <th>Producto</th> 
            <th width="75">UM</th>
            <th>Marca</th>
            <th width="75">Capacidad</th>
            <th width="100">Precio</th>
            <th width="125">Precio X UM</th>
            <th width="300">Supermercado</th>
            <th width="150">Fecha Cotización</th>
            <th width="100">Acciones</th>

        </tr>
    </thead>
    <tbody>
        <?php
           $query = "SELECT 	
                            cot.id,
                            cot.mes_compra,
                            sup.descripcion as 'descripcion_supermercado',
                            pro.descripcion as 'descripcion_producto',
                            mar.descripcion as 'descripcion_marca',
                            u.descripcion as 'descripcion_um',
                            cot.capacidad,
                            cot.precio,
                            cot.precio_por_um
                    FROM cotizador_mensual cot 	inner join supermercados sup on cot.supermercado = sup.id
                                                inner join productos pro on cot.producto = pro.id
                                                inner join marcas mar on cot.marca = mar.id
                                                inner join unidades u on cot.um = u.id
                    ORDER BY 2,3,4,5,6";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row = $result->fetch_assoc()) {?>
                <tr>
                    <td><?php echo $numero++; ?></td>
                    <td><?php echo $row['descripcion_producto']; ?></td>
                    <td><?php echo $row['descripcion_um']; ?></td>
                    <td><?php echo $row['descripcion_marca']; ?></td>
                    <td><?php echo $row['capacidad']; ?></td>
                    <td><?php echo formatoMonedaCLP($row['precio']); ?></td>
                    <td><?php echo formatoMonedaCLP($row['precio_por_um']); ?></td>
                    <td><?php echo $row['descripcion_supermercado']; ?></td>
                    <td><?php echo formatoFechaDMY($row['mes_compra']); ?></td>
                    <td>
                        <!-- <a href="editarProducto.php?id=<?php //echo $row['id']; ?>" class="btn btn-success btn-sm"><span class="material-icons align-bottom">edit</span></a> -->
                        <a href="eliminarproducto.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" title="Presione para eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta cotización?');"><span class="material-icons align-bottom">delete</span></a>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="9">No se encontraron productos.</td>
            </tr>
        <?php }
        $conn->close();
        ?>
    </tbody>
</table>