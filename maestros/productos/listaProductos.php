<?php

include("../../inc/connection.php");

?>


<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark sticky-top">
        <tr>
            <th width="50">N°</th>
            <th width="200">Codigo</th> 
            <th>Descripción</th>
            <th>Categoria</th>
            <th width="100">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php

        

        $query = "SELECT productos.id,productos.codigo, productos.descripcion, c.descripcion as 'descripcion_categoria', c.icono as 'icono_categoria'
                    FROM productos inner join categorias c on productos.categoria = c.id
                    WHERE productos.estado = 1 ORDER BY 2";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row = $result->fetch_assoc()) {?>
                <tr>
                    <td><?php echo $numero++; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><span class="material-symbols-outlined align-bottom"><?php echo $row['icono_categoria']; ?></span> <?php echo $row['descripcion_categoria']; ?></td>
                    <td>
                        <!-- <a href="editarProducto.php?id=<?php //echo $row['id']; ?>" class="btn btn-success btn-sm"><span class="material-icons align-bottom">edit</span></a> -->
                        <button href="eliminarproducto.php?id=<?php echo $row['id']; ?>" class="btn text-danger" title="Presione para eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');"><span class="material-symbols-outlined align-bottom">delete</span>
                        </button>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="5">No se encontraron productos.</td>
            </tr>
        <?php }
        $conn->close();
        ?>
    </tbody>
</table>