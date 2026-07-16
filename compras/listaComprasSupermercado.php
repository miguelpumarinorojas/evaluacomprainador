<?php

include("../inc/connection.php");
// include("../inc/funciones.php");


$mes_compra = $_GET['mes_compra'] ?? '';
$supermercado = $_GET['supermercado'] ?? '';

$filasGeneradas = [];
?>


<table class="table table-striped table-hover table-bordered table-sm">
    <thead class="table-dark sticky-top">
        <tr>
            <th width="5">N°</th>
            <th width="5">Supermercado</th>
            <th width="10">Cat.</th>
            <th width="200">Producto</th>
            <th width="10">UM</th>
            <th width="100">Marca</th>
            <th width="10">Cap.</th>
            <th width="75">Precio</th>
            <th width="75">Precio X UM</th>
            <th width="75">Cantidad</th>
            <th width="75">Total</th>
            <th width="75">Eliminar</th>

        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT 	t1.id,
                            t1.supermercado,
                            t3.logo,
                            t3.descripcion descripcion_supermercado,
                            t1.producto,
                            t2.descripcion descripcion_producto,
                            t5.icono icono_categoria,
                            t5.descripcion descripcion_categoria,
                            t1.um,
                            t4.descripcion descripcion_um,
                            t6.descripcion descripcion_marca,
                            t1.capacidad,
                            t1.precio,
                            t1.menor_precio_por_um,
                            t1.cantidad_compra,
                            t1.total_por_producto
                            
                    FROM lista_compras t1 	inner join productos t2 on t1.producto = t2.id
                                            inner join supermercados t3 on t1.supermercado = t3.id
                                            inner join unidades t4 on t1.um = t4.id
                                            inner join categorias t5 on t2.categoria = t5.id
                                            inner join marcas t6 on t1.marca = t6.id
                    WHERE t1.estado = 0
                    ORDER BY 4,7,5";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row_ppal = $result->fetch_assoc()) {
                $filasGeneradas[] = $numero;
        ?>
                <tr>
                    <td><?php echo $numero++; ?></td>
                    <td><img src="../maestros/supermercados/<?php echo $row_ppal['logo']; ?>" alt="Logo" width="50" height="50"></td>
                    <td><span class="material-symbols-outlined align-bottom" title="<?php echo $row_ppal['descripcion_categoria']; ?>"><?php echo $row_ppal['icono_categoria']; ?></span></td>
                    <td><?php echo $row_ppal['descripcion_producto']; ?></td>
                    <td><?php echo $row_ppal['descripcion_um']; ?></td>
                    <td><?php echo $row_ppal['descripcion_marca']; ?></td>
                    <td><?php echo $row_ppal['capacidad']; ?></td>
                    <td><?php echo formatoMonedaCLP($row_ppal['precio']); ?></td>
                    <td><?php echo formatoMonedaCLP($row_ppal['menor_precio_por_um']); ?></td>
                    <td><?php echo $row_ppal['cantidad_compra']; ?></td>
                    <td><?php echo formatoMonedaCLP($row_ppal['total_por_producto']); ?></td>
                    <td>
                        <form method="POST" action="eliminarProductoListaCompras.php" onsubmit="return confirm('¿Está seguro de eliminar este producto?. Esto es irreversible.');">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row_ppal['id']); ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm" name="btnEliminar">
                                <span class="material-icons align-bottom">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="12">No se encontraron productos.</td>
            </tr>
        <?php }
        $conn->close();
        ?>
    </tbody>
</table>
<form method="POST" action="registrarCompra.php" onsubmit="return confirm('¿Está seguro de registrar esta compra?');">
    <div class="card-footer text-end">
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-outline-success" name="btnRegistrarCompra"><span class="material-icons align-bottom">save</span> Registrar Compra</button>
        </div>
    </div>
</form>