<?php
require_once('../inc/connection.php');
include('../login/session.php');
session_variable('../');

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compras no planificadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..0" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon-16x16.png">
    <link rel="manifest" href="../img/site.webmanifest">
    <link rel="stylesheet" href="../css/styles.css">
    <!--datepicker css -->
    <link href="../css/bootstrap-datepicker.css" rel="stylesheet">
</head>

<body>
    <?php

    if (isset($_POST['btnRegistrar'])) {

            //define parámetros para pasar el insert a la tabla compras_no_planificadas
            $fecha_cotizacion = $_POST['fechaCompras'];
            $supermercado = $_POST['supermercado'];
            $producto = $_POST['producto'];
            $marca = $_POST['marca'];
            $um = $_POST['um'];
            $cantidad = $_POST['cantidad'];
            $capacidad = $_POST['capacidad'];
            $precio = $_POST['precio'];
            $precioporum = $_POST['precioporum'];
            $observaciones = $_POST['observaciones'];
            $total = $precio * $cantidad;
            $estado = 2; // Estado 2 para compras no planificadas

            // Insertar los datos en la tabla compras_no_planificadas
            $query_insert = "INSERT INTO lista_compras (fecha_cotizacion, supermercado, producto, marca, um, capacidad,  precio, menor_precio_por_um,  cantidad_compra, total_por_producto, estado, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param("siiiidddddis", $fecha_cotizacion, $supermercado, $producto, $marca, $um, $capacidad,   $precio, $precioporum, $cantidad,  $total,  $estado, $observaciones);
            $stmt_insert->execute();
            $stmt_insert->close();
          ?>
        <div class='alert alert-success notification alert-dismissible fade show text-center' role='alert' id='success-alert-v2'>
            Compra no planificada registrada exitosamente! <span class="material-symbols-outlined align-bottom">done_all</span>
        </div>
    <?php }  ?>
    <nav class="navbar bg-dark navbar-dark">
        <div class="container-fluid">
            <div>
                <span class="navbar-brand mb-0 h1"><span class="material-icons align-bottom">shopping_cart</span> EvaluaCompraInador</span>
                <br>
                <span class="navbar-text">Bienvenido: <?php echo $_SESSION['nombre'] ?> - Perfil: <?php echo $_SESSION['perfil'] == 1 ? 'Administrador' : 'Usuario'; ?></span>
            </div>
            <div class="badge align-bottom">
                <a href='../login/logout.php' class="text-white text-decoration-none">
                    <span class="material-symbols-outlined align-bottom" title="Presione para salir del sistema">
                        logout
                    </span>
                </a>
            </div>
        </div>
    </nav>
    <nav aria-label="breadcrumb" class="bg-light py-2 px-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="../" class="text-decoration-none text-dark">
                    <span class="material-icons align-bottom">home</span> Inicio
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><span class="material-icons align-bottom">add_shopping_cart</span> Registro de Compras no planificadas</li>
        </ol>
    </nav>
    <div class="container-fluid">
        <form action="" method="POST" class="needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate>
            <div class="row pt-2">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <span class="material-icons align-bottom">add_shopping_cart</span> Datos de la compra no planificada
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="fechaCompras" class="form-label"><span class="material-icons align-bottom">calendar_today</span> Fecha Compras</label>
                                    <input type="month" class="form-control" id="fechaCompras" name="fechaCompras"
                                        required autofocus>
                                    <div class="invalid-feedback">
                                        Seleccione una fecha.
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="supermercado" class="form-label"><span class="material-icons align-bottom">storefront</span> Supermercado</label>
                                    <select class="form-select" id="supermercado" name="supermercado" required>
                                        <option value="">Seleccione un supermercado</option>
                                        <?php
                                        $query = "SELECT id,descripcion FROM supermercados WHERE estado = 1 ORDER BY descripcion ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione un supermercado.
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <div class="col">
                                    <label for="producto" class="form-label"><span class="material-symbols-outlined align-bottom">inventory_2</span> Producto</label>
                                    <select class="form-select" id="producto" name="producto" required>
                                        <option value="">Seleccione un producto</option>
                                        <?php
                                        $query = "SELECT id,descripcion FROM productos WHERE estado = 1 ORDER BY descripcion ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione un producto.
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="um" class="form-label"><span class="material-symbols-outlined align-bottom">scale</span> UM</label>
                                    <select class="form-select" id="um" name="um" required>
                                        <option value="">Seleccione UM</option>
                                        <?php
                                        $query = "SELECT id,descripcion FROM unidades WHERE estado = 1 ORDER BY descripcion ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione una UM.
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="marca" class="form-label"><span class="material-symbols-outlined align-bottom">copyright</span> Marca</label>
                                    <select class="form-select" id="marca" name="marca" required>
                                        <option value="">Seleccione una marca</option>
                                        <?php
                                        $query = "SELECT id,descripcion FROM marcas WHERE estado = 1 ORDER BY descripcion ASC";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleccione una marca.
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="cantidad" class="form-label"><span class="material-symbols-outlined align-bottom">tag</span> Cantidad</label>
                                    <input type="number" step="0.01" class="form-control" id="cantidad" name="cantidad" required>
                                    <div class="invalid-feedback">
                                        Ingrese una cantidad.
                                    </div>
                                </div>

                            </div>
                            <div class="row pt-2">
                                <div class="col">
                                    <label for="capacidad" class="form-label"><span class="material-symbols-outlined align-bottom">tag</span> Capacidad</label>
                                    <input type="text" pattern="[0-9]+([.,][0-9]+)?" class="form-control" id="capacidad" name="capacidad" required>
                                    <div class="invalid-feedback">
                                        Ingrese una capacidad.
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="precio" class="form-label"><span class="material-symbols-outlined align-bottom">attach_money</span> Precio</label>
                                    <input type="number" class="form-control" id="precio" name="precio" required>
                                    <div class="invalid-feedback">
                                        Ingrese un precio.
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="precioporum" class="form-label"><span class="material-symbols-outlined align-bottom">attach_money</span> Precio por unidad</label>
                                    <input type="number" class="form-control" id="precioporum" name="precioporum" readonly>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <div class="col">
                                    <label for="observaciones" class="form-label"><span class="material-symbols-outlined align-bottom">note</span> Observaciones</label>
                                    <input type="text" class="form-control" id="observaciones" name="observaciones" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-outline-success" name="btnRegistrar"><span class="material-icons align-bottom">add_shopping_cart</span> Registrar compra</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-2">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <span class="material-icons align-bottom">list</span> Listas Compras no planificadas
                        </div>
                        <div class="card-body">
                            <div id="TABLA_DE_COMPRAS"></div>
                            <?php //include("listasDeComprasGeneradas.php"); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/locales/bootstrap-datepicker.es.js"></script>
    <script src="../js/calcularPrecio.js"></script>
    <script>
        $(document).ready(function() {
            $('#fechaCotizacion').change({
                function() {
                    var fechaSeleccionada = $(this).val();
                    console.log("Fecha seleccionada: " + fechaSeleccionada); // Agrega esta línea para depuración
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            CARGALISTADECOMPRAS();
            $('#mes_anio').on('change', function() {
                console.log('Fecha de cotización cambiada a: ' + $(this).val());
                CARGALISTADECOMPRAS();
            });
        });

        function CARGALISTADECOMPRAS() {
            $('#TABLA_DE_COMPRAS').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            $.ajax({
                type: "POST",
                url: "listaComprasNoPlanificadas.php",
                data: "FECHA_COTIZACION=" + $('#mes_anio').val(),
                success: function(r) {
                    $('#TABLA_DE_COMPRAS').html(r);
                }
            });
        }
    </script>
    <script>
        $('.datepicker').datepicker({
            language: 'es',
            format: 'dd-mm-yyyy',
            autoclose: 'on',
            todayHighlight: 'true',
            orientation: 'auto'
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            inicializarCalculoPrecioFormularioPrincipal();
        });
    </script>
    <script>
        $(".alert-dismissible").fadeIn(1000, 500).delay(3000).slideUp(500, function() {
            $(".alert-dismissible").alert('close');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>