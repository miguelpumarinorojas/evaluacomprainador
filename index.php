<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EvaluaCompraInador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    <link rel="manifest" href="img/site.webmanifest">
</head>

<body>
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
            <li class="breadcrumb-item active" aria-current="page"><span class="material-icons align-bottom">home</span> Inicio</li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mt-2">
                    <div class="card-body">
                        <a href="listacompras" class="text-decoration-none text-dark">
                            <h5 class="card-title"><span class="material-symbols-outlined align-bottom">looks_one</span> Lista de compras mensual</h5>
                        </a>
                        <p class="card-text">Seleccionar productos para lista de compras mensual</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card mt-2">
                    <div class="card-body">
                        <a href="cotizacompras" class="text-decoration-none text-dark">
                            <h5 class="card-title"><span class="material-symbols-outlined align-bottom">looks_two</span> Generar Listas de Cotizaciones por mes</h5>
                        </a>
                        <p class="card-text">Selecciona fecha y supermercado para registrar cotizaciones por supermercado</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card mt-2">
                    <div class="card-body">
                        <a href="valorizacompras" class="text-decoration-none text-dark">
                            <h5 class="card-title"><span class="material-symbols-outlined align-bottom">looks_3</span> Valorizar lista de compras</h5>
                        </a>
                        <p class="card-text">Generar lista de compras en base a evaluación de cotizaciones registradas por rango de fechas</p>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card mt-2">
                    <div class="card-body">
                        <a href="dashboard" class="text-decoration-none text-dark">
                            <h5 class="card-title"><span class="material-symbols-outlined align-bottom">finance</span> Dashboard</h5>
                        </a>
                        <p class="card-text">Indicadores de compras</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mt-2">
                    <div class="card-body">
                        <a href="maestros" class="text-decoration-none text-dark">
                            <h5 class="card-title"><span class="material-symbols-outlined align-bottom">list_alt</span> Maestros</h5>
                        </a>
                        <p class="card-text">Administrar maestros para la compra mensual</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.min.js" integrity="sha512-n/G+dROKbKL3GVngGWmWfwK0yPctjZQM752diVYnXZtD/48agpUKLIn0xDQL9ydZ91x6BiOmTIFwWjjFi2kEFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>