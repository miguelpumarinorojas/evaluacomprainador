<?php

include("../inc/connection.php");
include("../inc/funciones.php");

$filasGeneradas = [];
?>


<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark sticky-top">
        <tr>
            <th width="5" class="text-center">Supermercado</th>
            <th width="150">Fecha Creación</th>
            <th width="150">Fecha Cotización</th>
            <th width="75">Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT 	DISTINCT    t2.logo,
                                        t1.fecha_creacion,
                                        t1.mes_compra, 
                                        t2.id id_supermercado, 
                                        t2.descripcion descripcion_supermercado
                    FROM cotizador_mensual t1 	inner join supermercados t2 on t2.id = t1.supermercado
                    ORDER BY 1,3";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $numero = 1;
            while ($row_ppal = $result->fetch_assoc()) {
                $filasGeneradas[] = $numero;
        ?>
                <tr>
                    <td class="text-center">
                        <a href="../cotizador/index.php?mes_compra=<?php echo urlencode($row_ppal['mes_compra']); ?>&supermercado=<?php echo urlencode($row_ppal['id_supermercado']); ?>" class="text-success" title="Ingresar cotizaciones en lista">
                            <img href="../cotizador/index.php?mes_compra=<?php echo urlencode($row_ppal['mes_compra']); ?>&supermercado=<?php echo urlencode($row_ppal['id_supermercado']); ?>" src="../maestros/supermercados/<?php echo $row_ppal['logo']; ?>" height="50" alt="Logo del supermercado">
                        </a>
                    </td>
                    <td><?php echo formatoFechaHoraDMY($row_ppal['fecha_creacion']); ?></td>
                    <td><?php echo formatoFechaDMY($row_ppal['mes_compra']); ?></td>
                    <td>
                        <form method="POST" action="eliminarListaDeCompras.php" onsubmit="return confirm('¿Está seguro de eliminar esta lista de compras?. Esto es irreversible.');">
                            <input type="hidden" name="mes_compra" value="<?php echo htmlspecialchars($row_ppal['mes_compra']); ?>">
                            <input type="hidden" name="supermercado" value="<?php echo htmlspecialchars($row_ppal['id_supermercado']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="btnEliminar">
                                <span class="material-icons align-bottom">delete</span> Eliminar
                            </button>
                        </form>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="5">No se encontraron listas generadas.</td>
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