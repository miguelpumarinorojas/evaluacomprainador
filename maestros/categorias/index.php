<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="apple-touch-icon" sizes="180x180" href="../../img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/favicon-16x16.png">
    <link rel="manifest" href="../../img/site.webmanifest">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
    <?php

    if (isset($_POST['btnRegistrar']) && !empty($_POST['CodigoCategoria']) && !empty($_POST['DescripcionCategoria'])) {
        $codigoCategoria = $_POST['CodigoCategoria'];
        $descripcionCategoria = $_POST['DescripcionCategoria'];
        // Aquí puedes realizar la lógica para guardar la categoría en la base de datos o realizar otras acciones necesarias.
        $conn = new mysqli("localhost", "root", "", "evaluacomprainador");
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $validarCodigo = $conn->prepare("SELECT * FROM categorias WHERE codigo = ?");
        $validarCodigo->bind_param("s", $codigoCategoria);
        $validarCodigo->execute();
        $resultado = $validarCodigo->get_result();
        if ($resultado->num_rows > 0) {
            $validarCodigo->close();
            $conn->close();
    ?>
            <div class='alert alert-danger notification alert-dismissible fade show text-center' role='alert' id='success-alert-v2'>
                La categoría <?php echo $codigoCategoria; ?> ya se encuentra registrada! <span class="material-icons align-bottom">error</span>
            </div>
        <?php } else {
            $validarCodigo->close();


            $estadoCategoria = 1; // Asignar un valor predeterminado para el estado de la categoría
            $iconoCategoria = $_POST['IconoCategoria']; // Obtener el nombre del icono del formulario
            $stmt = $conn->prepare("INSERT INTO categorias (codigo, descripcion, estado, icono) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssi", $codigoCategoria, $descripcionCategoria, $estadoCategoria, $iconoCategoria);
            $stmt->execute();
            $stmt->close();
            $conn->close();


        ?>
            <div class='alert alert-success notification alert-dismissible fade show text-center' role='alert' id='success-alert-v2'>
                Categoría <?php echo $codigoCategoria . " - " . $descripcionCategoria; ?> registrada exitosamente! <span class="material-icons align-bottom">done</span>
            </div>
    <?php }
    }




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
                <a href="../../" class="text-decoration-none text-dark">
                    <span class="material-icons align-bottom">home</span> Inicio
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="../" class="text-decoration-none text-dark">
                    <span class="material-icons align-bottom">list_alt</span> Maestros
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <span class="material-icons align-bottom">category</span> Categorias
            </li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="row pt-2">
            <div class="col">
                <form action="" method="POST" class="needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate>
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <span class="material-icons align-bottom">add</span> Registro de Categorias
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="CodigoCategoria" class="form-label"><span class="material-icons align-bottom">label</span> Codigo de la Categoria</label>
                                        <input type="text" class="form-control" id="CodigoCategoria" name="CodigoCategoria" placeholder="Codigo de la Categoria" maxlength="20" required>
                                        <div class="invalid-feedback">
                                            Ingrese el codigo de la categoria.
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="DescripcionCategoria" class="form-label"><span class="material-icons align-bottom">text_fields</span> Descripción de la Categoria</label>
                                        <input type="text" class="form-control" id="DescripcionCategoria" name="DescripcionCategoria" placeholder="Descripción de la Categoria" maxlength="200" required>
                                        <div class="invalid-feedback">
                                            Ingrese la descripción de la categoria.
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="IconoCategoria" class="form-label"><span class="material-icons align-bottom">image</span> Nombre del icono</label>
                                        <input type="text" class="form-control" id="IconoCategoria" name="IconoCategoria" placeholder="Nombre del icono" maxlength="200" required>
                                        <div class="invalid-feedback">
                                            Ingrese el nombre del icono.
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success" name="btnRegistrar"><span class="material-icons align-bottom">save</span> Registrar</button>
            </div>
        </div>
        </form>
    </div>
    </div>
    <div class="row pt-2">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <span class="material-icons align-bottom">list</span> Lista de Categorias
                </div>
                <div class="card-body">
                    <?php include("listaCategorias.php"); ?>
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