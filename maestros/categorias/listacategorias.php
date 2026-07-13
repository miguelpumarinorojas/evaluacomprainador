<?php

include("../../inc/connection.php");

?>


<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark">
        <tr>
            <th width="50">N°</th>
            <th width="200">Codigo</th>
            <th width="50">Icono</th>
            <th>Descripción</th>
            <th width="100">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM categorias WHERE estado = 1 ORDER BY id DESC";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $numero++; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><span class="material-symbols-outlined align-bottom"><?php echo $row['icono']; ?></span></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td>
                        <!-- <a href="editarCategoria.php?id=<?php //echo $row['id']; 
                                                                ?>" class="btn btn-success btn-sm"><span class="material-icons align-bottom">edit</span></a> -->
                        <a href="eliminarcategoria.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" title="Presione para eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');"><span class="material-icons align-bottom">delete</span></a>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4">No se encontraron categorías.</td>
            </tr>
        <?php }
        $conn->close();
        ?>
    </tbody>
</table>