<?php

include("../inc/connection.php");
include("../inc/funciones.php");

$filasGeneradas = [];
?>


<table class="table table-striped table-hover table-bordered table-sm table-responsive">
    <thead class="table-dark sticky-top">
        <tr>
            <th width="5" class="text-center">Supermercado</th>
            <th width="300">Fecha Creación</th>
            <th width="300">Fecha Cotización</th>
            <th width="5">Eliminar</th>
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
                            <button type="submit" class="btn btn-outline-danger btn-sm" name="btnEliminar">
                                <span class="material-icons align-bottom">delete_forever</span>
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