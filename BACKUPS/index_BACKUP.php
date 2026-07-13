<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

    // Variables para persistir la selección de fecha y supermercado
    $fechaPersist = '';
    $supermercadoPersist = '';

    if (isset($_POST['btnRegistrar'])) {
        $fechaCotizacionInput = trim($_POST['fechaCotizacion']);
        $fechaCotizacionDate = DateTime::createFromFormat('d-m-Y', $fechaCotizacionInput);
        if ($fechaCotizacionDate instanceof DateTime) {
            $fechaCotizacionFormateada = $fechaCotizacionDate->format('Y-m-d');
        } else {
            $fechaCotizacionIso = DateTime::createFromFormat('Y-m-d', $fechaCotizacionInput);
            $fechaCotizacionFormateada = $fechaCotizacionIso instanceof DateTime ? $fechaCotizacionIso->format('Y-m-d') : $fechaCotizacionInput;
        }
        $supermercado = $_POST['Supermercado'];
        $codigoProducto = $_POST['CodigoProducto'];
        $marcaProducto = $_POST['marca'];
        $categoriaProducto = $_POST['CategoriaProducto'];
        $capacidadProducto = $_POST['capacidad'];
        $precioProducto = $_POST['precio'];
        $precioPorUmProducto = $_POST['precioporum'];


        // echo "<script>console.log('Fecha de cotización: " . $fechaCotizacion . "');</script>";
        // echo "<script>console.log('Supermercado: " . $supermercado . "');</script>";
        // echo "<script>console.log('Código del producto: " . $codigoProducto . "');</script>";
        // echo "<script>console.log('Marca del producto: " . $marcaProducto . "');</script>";
        // echo "<script>console.log('Categoría del producto: " . $categoriaProducto . "');</script>";
        // echo "<script>console.log('Capacidad del producto: " . $capacidadProducto . "');</script>";
        // echo "<script>console.log('Precio del producto: " . $precioProducto . "');</script>";
        // echo "<script>console.log('Precio por UM del producto: " . $precioPorUmProducto . "');</script>";


        // Aquí puedes realizar la lógica para guardar el producto en la base de datos o realizar otras acciones necesarias. 
        $conn = new mysqli("localhost", "root", "", "evaluacomprainador");
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }


        $stmt = $conn->prepare("INSERT INTO cotizador_mensual (mes_compra, supermercado, producto, marca, um, capacidad, precio, precio_por_um) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiiddd", $fechaCotizacionFormateada, $supermercado, $codigoProducto, $marcaProducto, $categoriaProducto, $capacidadProducto, $precioProducto, $precioPorUmProducto);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        // Persistir la fecha y supermercado después del registro exitoso
        $fechaPersist = $fechaCotizacionInput;
        $supermercadoPersist = $supermercado;



    ?>
        <div class='alert alert-success notification alert-dismissible fade show text-center' role='alert' id='success-alert-v2'>
            Cotización <?php //echo $codigoProducto . " - " . $descripcionProducto; 
                        ?> registrada exitosamente! <span class="material-icons align-bottom">done</span>
        </div>
    <?php }
    //}




    // echo $_POST['NombreProducto'] ?? 'No se ha enviado ningún nombre de producto.';

    ?>
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
            <li class="breadcrumb-item active" aria-current="page"><span class="material-icons align-bottom">dashboard</span> Cotizador</li>
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
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="fechaCotizacion" class="form-label"><span class="material-icons align-bottom">calendar_today</span> Fecha</label>
                                        <input type="date" class="form-control" id="fechaCotizacion" name="fechaCotizacion" value="<?php 
                                            if (!empty($fechaPersist)) {
                                                $fecha = DateTime::createFromFormat('d-m-Y', $fechaPersist);
                                                if ($fecha instanceof DateTime) {
                                                    echo $fecha->format('Y-m-d');
                                                } else {
                                                    echo $fechaPersist;
                                                }
                                            }
                                        ?>" required autofocus>
                                        <div class="invalid-feedback">
                                            Seleccione una fecha.
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="Supermercado" class="form-label"><span class="material-icons align-bottom">storefront</span> Supermercado</label>
                                        <select name="Supermercado" id="Supermercado" class="form-select" required>
                                            <option value="">Seleccione un supermercado</option>
                                            <?php
                                            include("../inc/connection.php");

                                            $query_select = "SELECT * FROM supermercados WHERE estado = 1 ORDER BY descripcion";
                                            $result_select = $conn->query($query_select);
                                            while ($row_select = $result_select->fetch_assoc()) {
                                                $selected = (!empty($supermercadoPersist) && $supermercadoPersist == $row_select['id']) ? 'selected' : '';
                                                echo "<option value='" . $row_select['id'] . "' " . $selected . ">" . $row_select['descripcion'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Seleccione un supermercado.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
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
                                        <label for="marca" class="form-label"><span class="material-icons align-bottom">copyright</span> Marca</label>
                                        <select name="marca" id="marca" class="form-select" required>
                                            <option value="">Seleccione una marca</option>
                                            <?php
                                            include("../inc/connection.php");

                                            $query_select = "SELECT * FROM marcas WHERE estado = 1 ORDER BY descripcion";
                                            $result_select = $conn->query($query_select);

                                            if ($result_select->num_rows > 0) {
                                                while ($row = $result_select->fetch_assoc()) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                                <?php }
                                            } else { ?>
                                                <option value="">No se encontraron marcas</option>
                                            <?php }
                                            $conn->close();
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Seleccione un marca
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="CategoriaProducto" class="form-label"><span class="material-icons align-bottom">list</span> UM</label>
                                        <select name="CategoriaProducto" id="CategoriaProducto" class="form-select" required>
                                            <option value="">Seleccione una UM</option>
                                            <?php
                                            include("../inc/connection.php");

                                            $query_select = "SELECT * FROM unidades WHERE estado = 1 ORDER BY descripcion";
                                            $result_select = $conn->query($query_select);

                                            if ($result_select->num_rows > 0) {
                                                while ($row = $result_select->fetch_assoc()) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                                <?php }
                                            } else { ?>
                                                <option value="">No se encontraron unidad</option>
                                            <?php }
                                            $conn->close();
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Seleccione una UM
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label for="capacidad" class="form-label"><span class="material-icons align-bottom">tag</span> Capacidad</label>
                                        <input type="text" class="form-control" pattern="[0-9]+([.,][0-9]+)?" id="capacidad" name="capacidad" required>
                                        <div class="invalid-feedback">
                                            Ingrese la capacidad del producto.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label for="precio" class="form-label"><span class="material-icons align-bottom">attach_money</span> Precio</label>
                                        <input type="number" class="form-control" id="precio" name="precio" required>
                                        <div class="invalid-feedback">
                                            Ingrese el precio del producto.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="precioporum" class="form-label"><span class="material-icons align-bottom">attach_money</span> Precio x UM</label>
                                        <input type="number" class="form-control" id="precioporum" name="precioporum" readonly>
                                        <div class="invalid-feedback">
                                            Ingrese el precio por unidad de medida.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success" name="btnRegistrar"><span class="material-icons align-bottom">save</span> Registrar</button>
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
                        <span class="material-icons align-bottom">list</span> Cotizador
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
        function calcularPrecioPorUM() {
            const capacidadInput = document.getElementById('capacidad');
            const precioInput = document.getElementById('precio');
            const precioPorUmInput = document.getElementById('precioporum');

            const capacidad = parseFloat(capacidadInput.value);
            const precio = parseFloat(precioInput.value);

            if (!Number.isFinite(capacidad) || !Number.isFinite(precio) || capacidad <= 0) {
                precioPorUmInput.value = '';
                return;
            }

            const resultado = precio / capacidad;
            precioPorUmInput.value = resultado.toFixed(0);
        }

        const capacidadInput = document.getElementById('capacidad');
        const precioInput = document.getElementById('precio');

        ['input', 'change'].forEach(evento => {
            capacidadInput.addEventListener(evento, calcularPrecioPorUM);
            precioInput.addEventListener(evento, calcularPrecioPorUM);
        });

        calcularPrecioPorUM();
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