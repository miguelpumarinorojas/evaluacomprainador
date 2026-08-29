<?php

include("../../inc/connection.php");

?>


<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark sticky-top">
        <tr>
            <th width="50">N°</th>
            <!-- <th width="200">Codigo</th> -->
            <th>Descripción</th>
            <th>Categoria</th>
            <th width="50">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php



        $query = "SELECT productos.id,productos.codigo, productos.descripcion, c.descripcion as 'descripcion_categoria', c.icono as 'icono_categoria', c.id as 'id_categoria'
                    FROM productos inner join categorias c on productos.categoria = c.id
                    WHERE productos.estado = 1 ORDER BY 2";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $numero++; ?></td>
                    <!-- <td><?php //echo $row['codigo']; ?></td> -->
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><span class="material-symbols-outlined align-bottom"><?php echo $row['icono_categoria']; ?></span> <?php echo $row['descripcion_categoria']; ?></td>
                    <td style="display: flex; gap: 15px;">
                        <!-- Button trigger modal -->
                        <a type="button" class="btn text-success" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['id']; ?>" title="Presione para modificar">
                            <span class="material-symbols-outlined align-bottom">edit</span>
                        </a>
                        <!-- Modal -->
                        <form action="modificarproducto.php?id=<?php echo $row['id']; ?>" method="POST">
                            <div class="modal fade" id="exampleModal<?php echo $row['id']; ?>" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $row['id']; ?>"><span class="material-symbols-outlined align-bottom">edit</span> Modificar Producto: <?php echo $row['descripcion']; ?></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row pt-2">
                                                <!-- <div class="col">
                                                    <label for="CodigoProducto" class="form-label"><span class="material-icons align-bottom">label</span> Codigo del Producto</label>
                                                    <input type="text" class="form-control" id="CodigoProducto" value="<?php //echo $row['codigo']; ?>" name="CodigoProducto" maxlength="20" disabled required>
                                                </div> -->
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col">
                                                    <label for="DescripcionProducto" class="form-label"><span class="material-icons align-bottom">text_fields</span> Descripción del Producto</label>
                                                    <input type="text" class="form-control" id="DescripcionProducto" value="<?php echo $row['descripcion']; ?>" name="DescripcionProducto" placeholder="Descripción del Producto" maxlength="200" required>
                                                </div>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col">
                                                    <label for="CategoriaProducto" class="form-label"><span class="material-icons align-bottom">list</span> Categoria</label>
                                                    <select name="CategoriaProducto" id="CategoriaProducto" class="form-select" required>
                                                        <option value="">Seleccione una categoría</option>
                                                        <?php
                                                        include("../../inc/connection.php");

                                                        $query_select = "SELECT * FROM categorias WHERE estado = 1 ORDER BY descripcion";
                                                        $result_select = $conn->query($query_select);

                                                        if ($result_select->num_rows > 0) {
                                                            while ($rowcat = $result_select->fetch_assoc()) { ?>
                                                                <option value="<?php echo $rowcat['id']; ?>" <?php if ($rowcat['id'] == $row['id_categoria']) {
                                                                                                                    echo "selected";
                                                                                                                } ?>><?php echo $rowcat['descripcion']; ?></option>
                                                            <?php }
                                                        } else { ?>
                                                            <option value="">No se encontraron categorías</option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><span class="material-symbols-outlined align-bottom">close</span> Cerrar</button>
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="btnModificar[]" value="<?php echo $row['id']; ?>" class="btn btn-success"><span class="material-symbols-outlined align-bottom">save</span> Modificar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <a href="eliminarproducto.php?id=<?php echo $row['id']; ?>" class="btn text-danger" title="Presione para eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');"><span class="material-symbols-outlined align-bottom">delete</span></a>
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