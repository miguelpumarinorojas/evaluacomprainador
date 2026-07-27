<?php
include('../inc/connection.php');
include("../login/session.php");
session_variable('../');
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- TOM SELECT CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.6.2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon-16x16.png">
    <link rel="manifest" href="../img/site.webmanifest">

</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0"></script>
<?php
include("../inc/connection.php");
?>

<body>
    <nav class="navbar bg-dark navbar-dark">
        <div class="container-fluid">
            <div>
                <span class="navbar-brand mb-0 h1"><span class="material-icons align-bottom">shopping_cart</span> EvaluaCompraInador</span>
                <br>
                <span class="navbar-text">Bienvenido: <?php echo $_SESSION['nombre']; ?></span>
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
            <li class="breadcrumb-item active" aria-current="page">
                <span class="material-icons align-bottom">attach_money</span> Historico precios
            </li>
        </ol>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card mt-2">
                    <div class="card-header">
                        <h5 class="card-title"><span class="material-symbols-outlined align-bottom">search</span> Buscar historial de precios</h5>
                        <p class="card-text">Selecciona producto y fecha para buscar su historial de precios</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label for="">Producto</label>
                                <select name="Producto" id="Producto" class="form-select select-beast" required>
                                    <option value="">-Ninguno-</option>
                                    <?php

                                    $query_select = "SELECT * FROM productos WHERE estado = 1 ORDER BY descripcion";
                                    $result_select = $conn->query($query_select);

                                    if ($result_select->num_rows > 0) {
                                        while ($row = $result_select->fetch_assoc()) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                        <?php }
                                    } else { ?>
                                        <option value="">No se encontraron productos</option>
                                    <?php }
                                    // $conn->close();
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="">Supermercado</label>
                                <select name="Supermercado" id="Supermercado" class="form-select select-beast" required>
                                    <option value="">-Ninguno-</option>
                                    <?php

                                    $query_select = "SELECT * FROM supermercados WHERE estado = 1 ORDER BY descripcion";
                                    $result_select = $conn->query($query_select);

                                    if ($result_select->num_rows > 0) {
                                        while ($row = $result_select->fetch_assoc()) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                        <?php }
                                    } else { ?>
                                        <option value="">No se encontraron supermercados</option>
                                    <?php }
                                    // $conn->close();
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="">Fecha</label>
                                <input type="month" name="Fecha" id="Fecha" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mt-2">
                    <div class="card-header">
                        <h5 class="card-title"><span class="material-symbols-outlined align-bottom">list_alt</span> Listado de precios de producto</h5>
                        <!-- <p class="card-text">Selecciona fecha y supermercado para registrar listas de cotizaciones</p> -->
                    </div>
                    <div class="card-body">
                        <div id="TABLA_PRODUCTOS_PRECIOS"></div>
                        <?php  // include('listaProductosPrecios.php'); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mt-2">
                    <div class="card-header">
                        <h5 class="card-title"><span class="material-symbols-outlined align-bottom">finance_mode</span> Evolución de precios por producto</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart_barras" style="width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.6.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.querySelectorAll('select[class*="select-beast"]').forEach(el => {
            new TomSelect(el, {
                create: true,
                allowEmptyOption: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
    <script>
        function formatearLabelMesAnio(fechaValor) {
            if (!fechaValor) return '';

            const valor = String(fechaValor).trim();
            const partes = valor.split('-');

            if (partes.length >= 2) {
                const anio = partes[0];
                const mesNumero = parseInt(partes[1], 10);
                if (!isNaN(mesNumero) && mesNumero >= 1 && mesNumero <= 12) {
                    const fecha = new Date(Number(anio), mesNumero - 1, 1);
                    return new Intl.DateTimeFormat('es-CL', {
                        month: 'long',
                        year: 'numeric'
                    }).format(fecha);
                }
            }

            const fechaParseada = new Date(valor);
            if (!isNaN(fechaParseada.getTime())) {
                return new Intl.DateTimeFormat('es-CL', {
                    month: 'long',
                    year: 'numeric'
                }).format(fechaParseada);
            }

            return valor;
        }

        function parsearMonto(valor) {
            if (valor === null || valor === undefined || valor === '') return 0;
            if (typeof valor === 'number') return Number.isFinite(valor) ? valor : 0;

            const limpio = String(valor)
                .replace(/\./g, '')
                .replace(/,/g, '.')
                .replace(/[^0-9.-]/g, '');

            const numero = Number(limpio);
            return Number.isFinite(numero) ? numero : 0;
        }

        function formatearMonedaCLP(valor) {
            const monto = parsearMonto(valor);
            return new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP',
                maximumFractionDigits: 0
            }).format(monto);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function() {
            CARGALISTAPRODUCTOS();
            $('#Producto, #Supermercado, #Fecha').change(function() {
                // console.log('Producto cambiado a: ' + $(this).val());
                CARGALISTAPRODUCTOS();
                CARGAGRAFICOCIRCULAR();
            });
        });

        function CARGALISTAPRODUCTOS() {
            $('#TABLA_PRODUCTOS_PRECIOS').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            $.ajax({
                type: "POST",
                url: "listaProductosPrecios.php",
                data: "PRODUCTO=" + $('#Producto').val() + "&SUPERMERCADO=" + $('#Supermercado').val() + "&FECHA_INICIO=" + $('#Fecha').val(),
                dataType: "html",
                success: function(r) {
                    $('#TABLA_PRODUCTOS_PRECIOS').html(r);
                }
            });
        }
        // Variable global para guardar la instancia del gráfico
        let chartCircular;

        function CARGAGRAFICOCIRCULAR() {
            $('#TABLA_PRODUCTOS_PRECIOS').html(
                '<div class="d-flex justify-content-center">' +
                '<div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">' +
                '<span class="visually-hidden">Loading...</span></div></div>'
            );

            $.ajax({
                type: "POST",
                url: "grafico_circular.php",
                data: {
                    producto: $('#Producto').val()
                }, // enviar parámetro
                dataType: "json",
                success: function(data) {
                    const labels = data.map(item => formatearLabelMesAnio(item.descr_supermercado));
                    const valores = data.map(item => parsearMonto(item.precio_promedio));

                    const colores = labels.map((_, i) => {
                        const palette = [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(153, 102, 255)',
                            'rgb(255, 159, 64)'
                        ];
                        return palette[i % palette.length];
                    });

                    const ctx = document.getElementById('myChart_barras').getContext('2d');

                    if (chartCircular) {
                        // Actualizar gráfico existente
                        chartCircular.data.labels = labels;
                        chartCircular.data.datasets[0].data = valores;
                        chartCircular.data.datasets[0].backgroundColor = colores;
                        chartCircular.update();
                    } else {
                        // Crear gráfico la primera vez
                        chartCircular = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Precio promedio por supermercado',
                                    data: valores,
                                    borderWidth: 1,
                                    borderColor: 'black',
                                    backgroundColor: colores
                                }]
                            },
                            options: {
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => {
                                                const etiqueta = context.dataset.label ? context.dataset.label + ': ' : '';
                                                return etiqueta + formatearMonedaCLP(context.parsed.y);
                                            }
                                        }
                                    },
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'top',
                                        formatter: (value) => formatearMonedaCLP(value),
                                        color: 'black',
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: (value) => formatearMonedaCLP(value)
                                        }
                                    }
                                }
                            }
                        });
                    }

                    $('#TABLA_PRODUCTOS_PRECIOS').html('');
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    $('#TABLA_PRODUCTOS_PRECIOS').html('<p class="text-danger">Error al cargar datos</p>');
                }
            });
        }

        // Enganchar la función al evento change del selector
        $('#Producto').on('change', function() {
            CARGAGRAFICOCIRCULAR();
        });
    </script>
</body>

</html>