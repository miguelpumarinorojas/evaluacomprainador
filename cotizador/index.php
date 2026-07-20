<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=list_alt_check" />
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

    include("../inc/funciones.php");
    include("../inc/connection.php");

    $mes_compra = $_GET['mes_compra'] ?? '';
    $supermercado = $_GET['supermercado'] ?? '';

    // Variables para persistir la selección de fecha y supermercado
    $fechaPersist = '';
    $supermercadoPersist = '';
    $alertaAdvertencia = '';
    $requiereConfirmacion = false;

    if (isset($_POST['btnRegistrar'])) {
        $confirmarGeneracion = isset($_POST['confirmar_generar']) && $_POST['confirmar_generar'] === '1';
        $fechaCotizacionInput = trim($_POST['fechaCotizacion']);
        $fechaCotizacionDate = DateTime::createFromFormat('d-m-Y', $fechaCotizacionInput);
        if ($fechaCotizacionDate instanceof DateTime) {
            $fechaCotizacionFormateada = $fechaCotizacionDate->format('Y-m-d');
        } else {
            $fechaCotizacionIso = DateTime::createFromFormat('Y-m-d', $fechaCotizacionInput);
            $fechaCotizacionFormateada = $fechaCotizacionIso instanceof DateTime ? $fechaCotizacionIso->format('Y-m-d') : $fechaCotizacionInput;
        }
        $supermercados = $_POST['Supermercado'] ?? [];
        if (!is_array($supermercados)) {
            $supermercados = [$supermercados];
        }
        $supermercados = array_values(array_filter($supermercados, static function ($valor) {
            return $valor !== '' && $valor !== null;
        }));
        $fechaPersist = $fechaCotizacionInput;
        $supermercadoPersist = $supermercados;

        // Aquí puedes realizar la lógica para guardar el producto en la base de datos o realizar otras acciones necesarias. 
        $conn = new mysqli("localhost", "root", "", "evaluacomprainador");
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        if (count($supermercados) === 0) {
            $conn->close();
            die("Debe seleccionar al menos un supermercado.");
        }

        $listaSupermercados = implode(',', array_map('intval', $supermercados));
        $sqlVerificacion = "SELECT COUNT(*) AS total FROM cotizador_mensual WHERE mes_compra = '" . $conn->real_escape_string($fechaCotizacionFormateada) . "' AND supermercado IN (" . $listaSupermercados . ")";
        $resultVerificacion = $conn->query($sqlVerificacion);
        if ($resultVerificacion && ($rowVerificacion = $resultVerificacion->fetch_assoc()) && (int) $rowVerificacion['total'] > 0) {
            if (!$confirmarGeneracion) {
                $alertaAdvertencia = 'Ya existen registros para la fecha y supermercados seleccionados. Los datos serán borrados y regenerados solo si confirmas.';
                $requiereConfirmacion = true;
            }
        }


        if (!$requiereConfirmacion) {
            $stmtGenerar = $conn->prepare("CALL sp_generar_precios(?, ?)");
            if (!$stmtGenerar) {
                $conn->close();
                die("No se pudo preparar el procedimiento almacenado.");
            }

            $stmtGenerar->bind_param("ss", $fechaCotizacionFormateada, $listaSupermercados);
            $stmtGenerar->execute();
            $stmtGenerar->close();

            while ($conn->more_results() && $conn->next_result()) {
                if ($res = $conn->store_result()) {
                    $res->free();
                }
            }
        }

        $conn->close();

        if (!$requiereConfirmacion) {
    ?>
            <div class='alert alert-success notification alert-dismissible fade show text-center' role='alert' id='success-alert-v2'>
                Cotización generada exitosamente! <span class="material-icons align-bottom">done</span>
            </div>
    <?php }
    }
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
            <span class="navbar-brand mb-0 h1"><span class="material-icons align-bottom">shopping_cart</span> EvaluaCompraInador</span>
            <a class="navbar-brand" href="#">
                <span class="material-icons align-bottom">app_registration</span>
            </a>
        </div>
    </nav>
    <nav aria-label="breadcrumb" class="bg-light py-2 px-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="../" class="text-decoration-none text-dark">
                    <span class="material-icons align-bottom">home</span> Inicio
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="../cotizacompras/index.php?mes_compra=<?php echo $mes_compra; ?>" class="text-decoration-none text-dark">
                    <span class="material-icons align-bottom">list_alt</span> Listas de Compras
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><span class="material-icons align-bottom">attach_money</span> Fecha Cotización: <?php echo formatoMesAño($mes_compra); ?></li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="row pt-2">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <div class="row">
                            <div class="col d-flex justify-content-start align-items-center">
                                <h3>
                                    <span class="material-icons align-bottom" style="font-size: 30px;">attach_money</span>Listado de precios
                                </h3>
                            </div>
                            <div class="col d-flex justify-content-end align-items-center"> <?php
                                                $querySupermercado = "SELECT logo FROM supermercados WHERE id = ?";
                                                $stmtSupermercado = $conn->prepare($querySupermercado);
                                                $stmtSupermercado->bind_param("i", $supermercado);
                                                $stmtSupermercado->execute();
                                                $stmtSupermercado->bind_result($logoSupermercado);
                                                $stmtSupermercado->fetch();
                                                $stmtSupermercado->close(); ?>
                                <img src="../maestros/supermercados/<?php echo $logoSupermercado; ?>" height="50">
                                <?php ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php include("listaCompras.php"); ?>
                    </div>
                </div>
            </div>

        </div>
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