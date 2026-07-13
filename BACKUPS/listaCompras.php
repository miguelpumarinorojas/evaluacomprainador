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

$filasGeneradas = [];
?>


<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark sticky-top">
        <tr>
            <th width="50">N°</th>
            <th width="150">Producto</th>
            <th width="150">Supermercado</th>
            <th width="75">UM</th>
            <th width="100">Marca</th>
            <th width="75">Capacidad</th>
            <th width="100">Precio</th>
            <th width="125">Precio X UM</th>
            <th width="150">Fecha Cotización</th>

        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT 	t1.mes_compra, 
                            t3.id id_producto, 
                            t3.descripcion descripcion_producto, 
                            t2.id id_supermercado, 
                            t2.descripcion descripcion_supermercado,
                            t1.marca,
                            t1.um,
                            t1.capacidad,
                            t1.precio,
                            t1.precio_por_um
                    FROM cotizador_mensual t1 	inner join supermercados t2 on t2.id = t1.supermercado
                                                inner join productos t3 on t3.id = t1.producto
                    ORDER BY 5,3";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row_ppal = $result->fetch_assoc()) {
                $filasGeneradas[] = $numero;
        ?>
                <tr data-mes-compra="<?php echo htmlspecialchars($row_ppal['mes_compra']); ?>"
                    data-producto="<?php echo htmlspecialchars($row_ppal['id_producto']); ?>"
                    data-supermercado="<?php echo htmlspecialchars($row_ppal['id_supermercado']); ?>">
                    <td><?php echo $numero++; ?></td>
                    <td><?php echo $row_ppal['descripcion_producto']; ?></td>
                    <td><?php echo $row_ppal['descripcion_supermercado']; ?></td>
                    <td><select name="UM" id="fila_<?php echo $numero; ?>_UM" class="form-select" required>
                            <option value=""></option>
                            <?php
                            $query_select = "SELECT * FROM unidades WHERE estado = 1 ORDER BY descripcion";
                            $result_select = $conn->query($query_select);

                            if ($result_select->num_rows > 0) {
                                while ($row = $result_select->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>" <?php if (isset($row_ppal['um']) && $row_ppal['um'] == $row['id']) echo 'selected'; ?>><?php echo $row['descripcion']; ?></option>
                                <?php }
                            } else { ?>
                                <option value="">No se encontraron unidad</option>
                            <?php }
                            // $conn->close();
                            ?>
                        </select></td>
                    <td><select name="marca" id="fila_<?php echo $numero; ?>_marca" class="form-select" required>
                            <option value=""></option>
                            <?php
                            $query_select = "SELECT * FROM marcas WHERE estado = 1 ORDER BY descripcion";
                            $result_select = $conn->query($query_select);

                            if ($result_select->num_rows > 0) {
                                while ($row = $result_select->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>" <?php if (isset($row_ppal['marca']) && $row_ppal['marca'] == $row['id']) echo 'selected'; ?>><?php echo $row['descripcion']; ?></option>
                                <?php }
                            } else { ?>
                                <option value="">No se encontraron marcas</option>
                            <?php }
                            // $conn->close();
                            ?>
                        </select></td>
                    <td><input type="text" class="form-control" pattern="[0-9]+([.,][0-9]+)?" id="fila_<?php echo $numero; ?>_capacidad" name="capacidad" value="<?php echo $row_ppal['capacidad']; ?>"></td>
                    <td><input type="number" class="form-control" id="fila_<?php echo $numero; ?>_precio" name="precio" value="<?php echo $row_ppal['precio']; ?>"></td>
                    <td><input type="number" class="form-control" id="fila_<?php echo $numero; ?>_precioporum" name="precioporum" readonly value="<?php echo $row_ppal['precio_por_um']; ?>"></td>
                    <td><?php echo formatoFechaDMY($row_ppal['mes_compra']); ?></td>
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

<script src="../js/calcularPrecio.js"></script>
<script>
    async function guardarFilaCotizacion(fila) {
        const mesCompra = fila.dataset.mesCompra;
        const producto = fila.dataset.producto;
        const supermercado = fila.dataset.supermercado;
        const umInput = fila.querySelector('select[name="UM"]');
        const marcaInput = fila.querySelector('select[name="marca"]');
        const capacidadInput = fila.querySelector('input[name="capacidad"]');
        const precioInput = fila.querySelector('input[name="precio"]');
        const precioPorUmInput = fila.querySelector('input[name="precioporum"]');

        if (!mesCompra || !producto || !supermercado) {
            return;
        }

        const payload = new URLSearchParams({
            mes_compra: mesCompra,
            producto: producto,
            supermercado: supermercado,
            um: umInput ? umInput.value : '',
            marca: marcaInput ? marcaInput.value : '',
            capacidad: capacidadInput ? capacidadInput.value : '',
            precio: precioInput ? precioInput.value : '',
            precio_por_um: precioPorUmInput ? precioPorUmInput.value : ''
        });

        try {
            const response = await fetch('actualizarCotizacionMensual.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: payload.toString()
            });

            if (!response.ok) {
                throw new Error('Error HTTP ' + response.status);
            }

            const resultado = await response.json();
            if (!resultado.success) {
                console.error(resultado.message || 'No fue posible guardar la fila');
            }
        } catch (error) {
            console.error('Error al guardar la cotización:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar cálculo de precio para cada fila generada
        const filasGeneradas = <?php echo json_encode($filasGeneradas); ?>;
        filasGeneradas.forEach(function(numero) {
            inicializarCalculoPrecioFila('fila_' + numero + '_');
        });

        document.querySelectorAll('tbody tr[data-mes-compra]').forEach(function(fila) {
            const capacidadInput = fila.querySelector('input[name="capacidad"]');
            const precioInput = fila.querySelector('input[name="precio"]');
            const umInput = fila.querySelector('select[name="UM"]');
            const marcaInput = fila.querySelector('select[name="marca"]');

            if (capacidadInput) {
                ['input', 'change'].forEach(function(evento) {
                    capacidadInput.addEventListener(evento, function() {
                        guardarFilaCotizacion(fila);
                    });
                });
            }

            if (precioInput) {
                ['input', 'change'].forEach(function(evento) {
                    precioInput.addEventListener(evento, function() {
                        guardarFilaCotizacion(fila);
                    });
                });
            }

            if (umInput) {
                umInput.addEventListener('change', function() {
                    guardarFilaCotizacion(fila);
                });
            }

            if (marcaInput) {
                marcaInput.addEventListener('change', function() {
                    guardarFilaCotizacion(fila);
                });
            }
        });
    });
</script>