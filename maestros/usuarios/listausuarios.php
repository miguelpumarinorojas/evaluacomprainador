<?php

include("../../inc/connection.php");

?>


<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark sticky-top">
        <tr>
            <th width="50">N°</th>
            <th width="200">Email</th>
            <th>Nombre</th>
            <th>Perfil</th>
            <th>Fecha Creacion</th>
            <th width="100">Estado</th>
            <th width="100">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM usuarios ORDER BY id DESC";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $numero++; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['perfil'] == 1 ? 'Administrador' : 'Usuario'; ?></td>
                    <td><?php echo $row['fecha_creacion']; ?></td>
                    <td><?php echo $row['estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <!-- <a href="editarUnidad.php?id=<?php //echo $row['id']; 
                                                            ?>" class="btn btn-success btn-sm"><span class="material-icons align-bottom">edit</span></a> -->
                        <button href="eliminarusuarios.php?id=<?php echo $row['id']; ?>" class="btn text-danger" title="Presione para eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');"><span class="material-symbols-outlined align-bottom">delete</span>
                        </button>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4">No se encontraron usuarios.</td>
            </tr>
        <?php }
        $conn->close();
        ?>
    </tbody>
</table>