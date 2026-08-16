<?php

include("../inc/connection.php");
include("../inc/funciones.php");

$filasGeneradas = [];
$fecha_cotizacion = $_POST['FECHA_COTIZACION'];

?>
<!-- <form method="POST" action="registrarCompra.php" onsubmit="return confirm('¿Está seguro de registrar esta compra?');">
    <div class="card-footer text-end">
        <div class="d-grid gap-2">
            <?php //if ($fecha_cotizacion != '') { 
            ?>
                <a href="listar.php?mes_compra=<?php //echo $fecha_cotizacion; 
                                                ?>" target="_blank" class="btn btn-outline-danger" name="btnRegistrarCompra">
                    <span class="material-icons align-bottom">picture_as_pdf</span> Imprimir PDF
                </a>
            <?php //} 
            ?>
        </div>
    </div>
</form> -->

<?php

if (empty($fecha_cotizacion)) { ?>
    <div class="alert alert-warning text-center" role="alert">
        <h5>Seleccione una fecha para mostrar los productos.</h5>
    </div>
<?php } else { ?>

    <table class="table table-striped table-hover table-bordered table-sm table-responsive">
        <thead class="table-dark sticky-top">
            <tr>
                <th width="5">N°</th>
                <th style="width: auto;">Producto</th>
                <th style="width: auto;">Cant.</th>
                <th style="width: auto;">UM</th>
                <th style="width: auto;">Marca</th>
                <th style="width: auto;">Capacidad</th>
                <?php

                // Obtener los supermercados
                $supermercados = [];
                ?>

                <?php if (!empty($fecha_cotizacion)) {
                    $query_supermercado = "SELECT DISTINCT t2.id id_supermercado, t2.descripcion descripcion_supermercado 
                            FROM listacomprasprecios t1 INNER JOIN supermercados t2 on t1.id_supermercado = t2.id
                            WHERE mes_compra = '$fecha_cotizacion'
                            ORDER BY 1;";
                    $result_supermercado = $conn->query($query_supermercado);
                    if ($result_supermercado && $result_supermercado->num_rows > 0) {
                        while ($row_supermercado = $result_supermercado->fetch_assoc()) {
                            $supermercados[] = $row_supermercado;
                        }
                        $result_supermercado->free();
                    }
                }

                if (!empty($supermercados)) {
                    foreach ($supermercados as $row_supermercado) { ?>
                        <th class="text-center"><?php echo $row_supermercado['descripcion_supermercado']; ?></th>
                <?php }
                } ?>
            </tr>
        </thead>
        <tbody>

            <?php
            $unidades = [];
            $query_um = "SELECT * FROM unidades WHERE estado = 1 ORDER BY descripcion";
            $result_um = $conn->query($query_um);
            if ($result_um && $result_um->num_rows > 0) {
                while ($row_um = $result_um->fetch_assoc()) {
                    $unidades[] = $row_um;
                }
                $result_um->free();
            }

            $marcas = [];
            $query_marcas = "SELECT * FROM marcas WHERE estado = 1 ORDER BY descripcion";
            $result_marcas = $conn->query($query_marcas);
            if ($result_marcas && $result_marcas->num_rows > 0) {
                while ($row_marcas = $result_marcas->fetch_assoc()) {
                    $marcas[] = $row_marcas;
                }
                $result_marcas->free();
            }

            $productosPorDescripcion = [];
            $query_productos = "SELECT id, descripcion FROM productos WHERE estado = 1 ORDER BY descripcion";
            $result_productos = $conn->query($query_productos);
            if ($result_productos && $result_productos->num_rows > 0) {
                while ($row_producto = $result_productos->fetch_assoc()) {
                    $productosPorDescripcion[$row_producto['descripcion']] = $row_producto['id'];
                }
                $result_productos->free();
            }

            $registrosCompra = [];
            $query_registros = "SELECT id, id_producto, id_supermercado, UM, marca, capacidad, precio 
                                FROM listacomprasprecios 
                                WHERE mes_compra = '$fecha_cotizacion'";
            $result_registros = $conn->query($query_registros);
            if ($result_registros && $result_registros->num_rows > 0) {
                while ($row_registro = $result_registros->fetch_assoc()) {
                    $registrosCompra[$row_registro['id_producto']][$row_registro['id_supermercado']] = $row_registro;
                }
                $result_registros->free();
            }

            $query = "CALL sp_pivot_listacompras ('$fecha_cotizacion')";

            $result = $conn->query($query);
            if ($result && $result->num_rows > 0) {
                $numero = 1;
                while ($row_ppal = $result->fetch_assoc()) {
                    $filaNumero = $numero++;
                    $idProducto = $productosPorDescripcion[$row_ppal['descripcion_producto']] ?? '';
                    $registroGrupo = [];
                    if ($idProducto !== '' && isset($registrosCompra[$idProducto])) {
                        $registroGrupo = $registrosCompra[$idProducto];
                    }
            ?>
                    <tr>
                        <td class="text-center"><?php echo $filaNumero; ?></td>
                        <td><?php echo $row_ppal['descripcion_producto']; ?></td>
                        <td><input type="number" class="form-control js-autosave-producto" data-field="cantidad" data-id-producto="<?php echo htmlspecialchars((string) $idProducto); ?>" data-mes-compra="<?php echo htmlspecialchars($fecha_cotizacion); ?>" name="cantidad_<?php echo $filaNumero; ?>" value="<?php echo (empty($row_ppal['cantidad']) || (float)$row_ppal['cantidad'] == 0) ? '' : $row_ppal['cantidad']; ?>"></td>
                        <td>
                            <select name="UM" class="form-select js-autosave-producto" data-field="UM" data-id-producto="<?php echo htmlspecialchars((string) $idProducto); ?>" data-mes-compra="<?php echo htmlspecialchars($fecha_cotizacion); ?>">
                                <option value=""></option>
                                <?php foreach ($unidades as $row_um) {
                                    $selected = ($row_um['id'] == $row_ppal['UM']) ? 'selected' : ''; ?>
                                    <option value="<?php echo $row_um['id']; ?>" <?php echo $selected; ?>><?php echo $row_um['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="marca" class="form-select js-autosave-producto" data-field="marca" data-id-producto="<?php echo htmlspecialchars((string) $idProducto); ?>" data-mes-compra="<?php echo htmlspecialchars($fecha_cotizacion); ?>">
                                <option value=""></option>
                                <?php foreach ($marcas as $row_marcas) {
                                    $selected = ($row_marcas['id'] == $row_ppal['marca']) ? 'selected' : ''; ?>
                                    <option value="<?php echo $row_marcas['id']; ?>" <?php echo $selected; ?>><?php echo $row_marcas['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control js-autosave-producto" data-field="capacidad" data-id-producto="<?php echo htmlspecialchars((string) $idProducto); ?>" data-mes-compra="<?php echo htmlspecialchars($fecha_cotizacion); ?>" pattern="[0-9]+([.,][0-9]+)?" name="capacidad" value="<?php if ($row_ppal['capacidad'] == 0) {
                                                                                                                                echo '';
                                                                                                                            } else {
                                                                                                                                echo $row_ppal['capacidad'];
                                                                                                                            } ?>">
                        </td>
                        <?php foreach ($supermercados as $row_supermercado) {
                            $id_supermercado = $row_supermercado['id_supermercado'];
                            $descripcion_supermercado = str_replace(' ', '_', $row_supermercado['descripcion_supermercado']);
                            $registroActual = $registroGrupo[$id_supermercado] ?? [];
                            $precioFila = $registroActual['precio'] ?? ($row_ppal[$descripcion_supermercado . '_precio'] ?? '');
                            $registroId = $registroActual['id'] ?? '';

                        ?>
                            <td>
                                <input type="text"
                                    class="form-control js-autosave-precio"
                                    data-field="precio"
                                    data-registro-id="<?php echo htmlspecialchars((string) $registroId); ?>"
                                    data-id-producto="<?php echo htmlspecialchars((string) $idProducto); ?>"
                                    data-id-supermercado="<?php echo htmlspecialchars((string) $id_supermercado); ?>"
                                    data-mes-compra="<?php echo htmlspecialchars($fecha_cotizacion); ?>"
                                    pattern="[0-9]+([.,][0-9]+)?"
                                    name="precio_<?php echo $id_supermercado; ?>"
                                    value="<?php echo (empty($precioFila) || (float)$precioFila == 0) ? '' : $precioFila; ?>">
                            </td>
                        <?php } ?>
                    </tr>
                <?php }
                $result->free();
                while ($conn->more_results() && $conn->next_result()) {
                    if ($res = $conn->store_result()) {
                        $res->free();
                    }
                }
            } else { ?>
                <div class="alert alert-danger text-center" role="alert">
                    <h5>No se encontraron listas generadas</h5>
                </div>
        <?php }
        } ?>

        <?php
        $conn->close();
        ?>
        </tbody>
    </table>

    <script>
        (function() {
            const endpoint = 'actualizarCampoListaCompras.php';

            function normalizarValor(campo, valor) {
                if (valor === null || valor === undefined) {
                    return '';
                }

                if (campo === 'precio' || campo === 'capacidad' || campo === 'cantidad') {
                    const limpio = String(valor).trim();
                    return limpio === '' ? '0' : limpio;
                }

                return String(valor);
            }

            async function guardarCambio(datos) {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: new URLSearchParams(datos)
                });

                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'No se pudo guardar el cambio.');
                }

                return payload;
            }

            function registrarAutosave(elemento) {
                const campo = elemento.dataset.field;
                const valor = normalizarValor(campo, elemento.value);

                if (elemento.dataset.registroId) {
                    if (valor === '' && campo !== 'precio') {
                        return;
                    }

                    guardarCambio({
                        action: 'actualizar_por_id',
                        id: elemento.dataset.registroId,
                        campo: campo,
                        valor: valor
                    }).catch((error) => {
                        alert(error.message);
                    });
                    return;
                }

                if (elemento.dataset.idProducto && elemento.dataset.mesCompra) {
                    if (valor === '' && campo !== 'precio') {
                        return;
                    }

                    guardarCambio({
                        action: 'actualizar_por_producto',
                        id_producto: elemento.dataset.idProducto,
                        mes_compra: elemento.dataset.mesCompra,
                        campo: campo,
                        valor: valor
                    }).catch((error) => {
                        alert(error.message);
                    });
                }
            }

            document.querySelectorAll('.js-autosave-producto, .js-autosave-precio').forEach((elemento) => {
                if (elemento.tagName === 'SELECT') {
                    elemento.addEventListener('change', function() {
                        registrarAutosave(this);
                    });
                    return;
                }

                elemento.addEventListener('blur', function() {
                    registrarAutosave(this);
                });
            });
        })();
    </script>