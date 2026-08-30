<?php
require_once('../inc/connection.php');
include('../login/session.php');
session_variable('../');

$mes_compra = $_GET['mes_compra'] ?? '';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listas de compras mensual</title>
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

    $mes_compra = $_GET['mes_compra'] ?? '';
    $fecha_cotizacion = $_POST['fechaCotizacion'] ?? '';

    if (isset($_POST['btnRegistrar'])) {


        include("../inc/connection.php");
        include("../inc/funciones.php");

        if (!empty($_POST['productos']) && !empty($_POST['cantidades'])) {
            $productos   = $_POST['productos'];
            $cantidades = $_POST['cantidades'];

            // Recorrer ambos arrays con el mismo índice
            foreach ($productos as $i => $producto) {
                $cantidad = isset($cantidades[$i]) ? $cantidades[$i] : 0;

                // Validar que el producto no esté vacío
                if (!empty($producto)) {

                    $check = "SELECT id FROM lista_compras_mensual 
                  WHERE mes_compra = '$fecha_cotizacion' 
                  AND producto = '$producto' 
                  LIMIT 1";
                    $res = $conn->query($check);

                    if ($res->num_rows > 0) {
                        // Ya existe el registro
                        if ($cantidad > 0) {
                            // Actualizar cantidad
                            $query = "UPDATE lista_compras_mensual 
                          SET cantidad = $cantidad, 
                              fecha_creacion = NOW(), 
                              estado = 1 
                          WHERE mes_compra = '$fecha_cotizacion' 
                          AND producto = '$producto'";
                        } else {
                            // Eliminar si la cantidad es cero o vacía
                            $query = "DELETE FROM lista_compras_mensual 
                          WHERE mes_compra = '$fecha_cotizacion' 
                          AND producto = '$producto'";
                        }
                    } else {
                        // No existe el registro, insertar solo si cantidad > 0
                        if ($cantidad > 0) {
                            $query = "INSERT INTO lista_compras_mensual 
                          (fecha_creacion, mes_compra, producto, cantidad, estado) 
                          VALUES (NOW(), '$fecha_cotizacion', '$producto', $cantidad, 1)";
                        } else {
                            $query = null; // no hacer nada si no existe y cantidad = 0
                        }
                    }

                    // Ejecutar la query si corresponde
                    if ($query) {
                        $executionResult = $conn->query($query);
                        if (!$executionResult) {
                            $alertaAdvertencia = "Error al registrar la lista de compras mensual: " . $conn->error;
                        }
                    }
                }
            }
        } else { ?>
            <div class='alert alert-danger notification alert-dismissible fade show text-center' role='alert' id='success-alert-v2'>
                No seleccionaste ningun producto! <span class="material-icons align-bottom">warning</span>
            </div>
        <?php  }

        $conn->close();


        ?>
        <div class='alert alert-success notification alert-dismissible fade show text-center' role='alert' id='success-alert-v2'>
            Lista de compras registrada exitosamente para <?php echo formatoMesAño($fecha_cotizacion); ?>! <span class="material-icons align-bottom">done</span>
        </div>
    <?php  }
    //}




    // echo $_POST['NombreProducto'] ?? 'No se ha enviado ningún nombre de producto.';

    ?>
    <?php if (!empty($alertaAdvertencia)) { ?>
        <div class="alert alert-warning alert-dismissible notification fade show text-center" role="alert">
            <?php echo $alertaAdvertencia; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>
    <nav class="navbar bg-dark navbar-dark">
        <div class="container-fluid">
            <div>
                <span class="navbar-brand mb-0 h1"><span class="material-icons align-bottom">shopping_cart</span> EvaluaCompraInador</span>
                <br>
                <span class="navbar-text">Bienvenido: <?php echo $_SESSION['nombre'] ?> - Perfil: <?php echo $_SESSION['perfil'] == 1 ? 'Administrador' : ($_SESSION['perfil'] == 2 ? 'Usuario' : 'Seleccionar compra mensual'); ?></span>
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
                <?php if ($_SESSION['perfil'] == 3) { ?>
                    <span class="material-icons align-bottom">home</span> Inicio
                <?php } else { ?>
                    <a href="../" class="text-decoration-none text-dark">
                        <span class="material-icons align-bottom">home</span> Inicio
                    </a>
                <?php } ?>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><span class="material-icons align-bottom">add_shopping_cart</span> Listas de compras mensual</li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php if ($_SESSION['perfil'] == 3) { ?>
                    <span class="material-symbols-outlined align-bottom">add</span> Agregar Producto
                <?php } else { ?>
                    <a href="#AgregarProductoModal" data-bs-toggle="modal" class="text-decoration-none text-dark">
                        <span class="material-symbols-outlined align-bottom">add</span> Agregar Producto
                    </a>
                <?php } ?>
            </li>
        </ol>
    </nav>
    <!-- Modal -->
    <form action="registrar_material.php" method="POST" class="needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate>
        <div class="modal fade" id="AgregarProductoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="AgregarProductoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">

                        <h1 class="modal-title fs-5" id="AgregarProductoModalLabel">
                            <span class="material-symbols-outlined align-bottom">add</span>
                            Agregar Producto
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row pt-2">
                            <div class="col">
                                <label for="NombreProducto">
                                    <span class="material-symbols-outlined align-bottom">label</span> Descripcion del Producto
                                </label>
                                <input type="text" class="form-control" id="NombreProducto" name="NombreProducto" placeholder="Ingrese el nombre del Producto" required>
                                <div class="invalid-feedback">
                                    Ingrese la descripción del producto.
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col">
                                <label for="CategoriaProducto">
                                    <span class="material-symbols-outlined align-bottom">list</span> Categoría del Producto
                                </label>
                                <select class="form-select" id="CategoriaProducto" name="CategoriaProducto" required>
                                    <option value="">Seleccione una categoría</option>
                                    <?php
                                    include("../inc/connection.php");

                                    $query_select = "SELECT * FROM categorias WHERE estado = 1 ORDER BY descripcion";
                                    $result_select = $conn->query($query_select);

                                    if ($result_select->num_rows > 0) {
                                        while ($row = $result_select->fetch_assoc()) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                        <?php }
                                    } else { ?>
                                        <option value="">No se encontraron categorías</option>
                                    <?php }
                                    $conn->close();
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Ingrese la categoría del Producto.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <span class="material-symbols-outlined align-bottom">close</span> Cerrar
                        </button>
                        <button type="submit" class="btn btn-success" id="btnRegistrarMaterial">
                            <span class="material-symbols-outlined align-bottom">save</span> Registrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="container-fluid">
        <form action="" method="POST" class="needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate>
            <div class="row pt-2">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <span class="material-icons align-bottom">list</span> Selecciona la fecha de la lista de compras mensual
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <!-- <label for="fechaCotizacion" class="form-label"><span class="material-icons align-bottom">calendar_today</span> Fecha lista de compras</label> -->
                                        <input type="month" class="form-control" id="fechaCotizacion" name="fechaCotizacion"
                                            value="<?php echo $mes_compra; ?>" required autofocus <?php if ($_SESSION['perfil'] == 3) echo 'disabled'; ?>>
                                        <div class="invalid-feedback">
                                            Seleccione una fecha.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-outline-success" name="btnRegistrar" <?php if ($mes_compra == '' && $_SESSION['perfil'] == 3) {
                                                                                                                echo 'disabled';
                                                                                                            } ?>><span class="material-icons align-bottom">add_shopping_cart</span> Generar Lista de compras mensual</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-2">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <span class="material-icons align-bottom">attach_money</span> Listas Productos de compras mensual
                        </div>
                        <div class="card-body">
                            <div id="TABLA_DE_COMPRAS"></div>
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
            CARGALISTACOTIZACIONES();
            $('#fechaCotizacion').change(function() {
                console.log('Fecha de cotización cambiada a: ' + $(this).val());
                CARGALISTACOTIZACIONES();
            });
        });

        function CARGALISTACOTIZACIONES() {
            $('#TABLA_DE_COMPRAS').html('<div class="d-flex justify-content-center"><div class="spinner-border text-secondary" style="width: 4rem; height: 4rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            $.ajax({
                type: "POST",
                url: "listasDeComprasMensual.php",
                data: "FECHA_COTIZACION=" + $('#fechaCotizacion').val(),
                success: function(r) {
                    $('#TABLA_DE_COMPRAS').html(r);
                }
            });
        }
    </script>
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