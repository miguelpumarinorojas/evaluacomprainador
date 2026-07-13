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

    // Variables para persistir la selección de fecha y supermercado
    $fechaInicialPersist = '';
    $fechaFinalPersist = '';
    $tipoMensaje = $_GET['tipo'] ?? '';
    $mensajeFlash = $_GET['mensaje'] ?? '';

    if (isset($_POST['btnRegistrar'])) {

        $fechaInicialInput = trim($_POST['fechaInicial']);
        $fechaFinalInput = trim($_POST['fechaFinal']);


        if ($fechaFinalInput < $fechaInicialInput) {
            $alertaAdvertencia = "Fecha final no puede ser menor a fecha inicial.";
        } else {
            $alertaAdvertencia = '';
        }

        $producto = trim($_POST['CodigoProducto']);
        $cantidad = floatval($_POST['cantidad']);

        $fechaInicialDate = DateTime::createFromFormat('d-m-Y', $fechaInicialInput);
        $fechaFinalDate = DateTime::createFromFormat('d-m-Y', $fechaFinalInput);

        if ($fechaInicialDate instanceof DateTime) {
            $fechaInicialFormateada = $fechaInicialDate->format('Y-m-d');
        } else {
            $fechaInicialIso = DateTime::createFromFormat('Y-m-d', $fechaInicialInput);
            $fechaInicialFormateada = $fechaInicialIso instanceof DateTime ? $fechaInicialIso->format('Y-m-d') : $fechaInicialInput;
        }

        if ($fechaFinalDate instanceof DateTime) {
            $fechaFinalFormateada = $fechaFinalDate->format('Y-m-d');
        } else {
            $fechaFinalIso = DateTime::createFromFormat('Y-m-d', $fechaFinalInput);
            $fechaFinalFormateada = $fechaFinalIso instanceof DateTime ? $fechaFinalIso->format('Y-m-d') : $fechaFinalInput;
        }
        $fechaInicialPersist = $fechaInicialInput;
        $fechaFinalPersist = $fechaFinalInput;

        // Aquí puedes realizar la lógica para guardar el producto en la base de datos o realizar otras acciones necesarias. 
        $conn = new mysqli("localhost", "root", "", "evaluacomprainador");
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }


        $stmtGenerar = $conn->prepare("CALL sp_precio_menor_por_um(?, ?, ?, ?)");
        if (!$stmtGenerar) {
            $conn->close();
            die("No se pudo preparar el procedimiento almacenado.");
        }


        $stmtGenerar->bind_param("ssid", $fechaInicialFormateada, $fechaFinalFormateada, $producto, $cantidad);
        $stmtGenerar->execute();

        if ($stmtGenerar->affected_rows === 0) {
            $alertaAdvertencia = "No se encontraron resultados para el producto seleccionado en el rango de fechas proporcionado.";
        } else { ?>
            <div class='alert alert-success notification alert-dismissible fade show text-center' role='alert' id='success-alert-v2'>
                Producto registrado con el mejor precio!! <span class="material-icons align-bottom">done</span>
            </div>
        <?php  }

        $stmtGenerar->close();

        while ($conn->more_results() && $conn->next_result()) {
            if ($res = $conn->store_result()) {
                $res->free();
            }
        }


        $conn->close();

        ?>
        <?php if (!empty($alertaAdvertencia)) { ?>
            <div class="alert alert-warning alert-dismissible notification fade show text-center" role="alert">
                <?php echo $alertaAdvertencia; ?> <span class="material-icons align-bottom">error</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php }
    } ?>
    <?php if (!empty($mensajeFlash)) { ?>
        <div class="container-fluid">
            <div class="alert alert-<?php echo htmlspecialchars($tipoMensaje ?: 'info'); ?> notification alert-dismissible fade show text-center" role="alert">
                <?php echo htmlspecialchars($mensajeFlash); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php } ?>
    <nav class="navbar bg-dark navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1"><span class="material-icons align-bottom">shopping_cart</span> EvaluaCompraInador</span>
            <a class="navbar-brand" href="#">
                <!-- <img src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="Bootstrap" width="30" height="24"> -->
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
                <span class="material-icons align-bottom">shopping_cart</span> Crear lista de compras
            </li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="row pt-2">
            <div class="col">
                <form action="" method="POST" class="needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate>
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <span class="material-icons align-bottom">add</span> Lista de compras
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="fechaInicial" class="form-label"><span class="material-icons align-bottom">date_range</span> Fecha Inicial</label>
                                        <input type="date" class="form-control" id="fechaInicial" name="fechaInicial" value="<?php
                                                                                                                                if (!empty($fechaInicialPersist)) {
                                                                                                                                    $fecha = DateTime::createFromFormat('d-m-Y', $fechaInicialPersist);
                                                                                                                                    if ($fecha instanceof DateTime) {
                                                                                                                                        echo $fecha->format('Y-m-d');
                                                                                                                                    } else {
                                                                                                                                        echo $fechaInicialPersist;
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                ?>" required autofocus>
                                        <div class="invalid-feedback">
                                            Seleccione una fecha inicio.
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="fechaFinal" class="form-label"><span class="material-icons align-bottom">date_range</span> Fecha Final</label>
                                        <input type="date" class="form-control" id="fechaFinal" name="fechaFinal" value="<?php
                                                                                                                            if (!empty($fechaFinalPersist)) {
                                                                                                                                $fecha = DateTime::createFromFormat('d-m-Y', $fechaFinalPersist);
                                                                                                                                if ($fecha instanceof DateTime) {
                                                                                                                                    echo $fecha->format('Y-m-d');
                                                                                                                                } else {
                                                                                                                                    echo $fechaFinalPersist;
                                                                                                                                }
                                                                                                                            }
                                                                                                                            ?>" required autofocus>
                                        <div class="invalid-feedback">
                                            Seleccione una fecha final.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="CodigoProducto" class="form-label"><span class="material-icons align-bottom">inventory_2</span> Producto</label>
                                        <select name="CodigoProducto" id="CodigoProducto" class="form-select" required>
                                            <option value="">Seleccione un producto</option>
                                            <?php
                                            include("../inc/connection.php");

                                            $query_select = "SELECT * FROM productos WHERE estado = 1 ORDER BY descripcion";
                                            $result_select = $conn->query($query_select);

                                            if ($result_select->num_rows > 0) {
                                                while ($row = $result_select->fetch_assoc()) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                                <?php }
                                            } else { ?>
                                                <option value="">No se encontraron productos</option>
                                            <?php }
                                            $conn->close();
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Seleccione un producto
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="cantidad" class="form-label"><span class="material-icons align-bottom">tag</span> Cantidad</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                                        <div class="invalid-feedback">
                                            Ingrese la cantidad del producto.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success" name="btnRegistrar"><span class="material-icons align-bottom">search</span> Buscar mejor precio</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <div class="row">
                            <div class="col d-flex justify-content-start align-items-center">
                                <span class="material-icons align-bottom">shopping_cart</span> Lista de compras generada
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php include("listaComprasSupermercado.php"); ?>
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
        $(".alert-dismissible").fadeIn(1000, 500).delay(5000).slideUp(500, function() {
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