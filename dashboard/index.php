<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EvaluaCompraInador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="apple-touch-icon" sizes="180x180" href="../img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon-16x16.png">
    <link rel="manifest" href="img/site.webmanifest">

</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0"></script>

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
            <li class="breadcrumb-item active" aria-current="page">
                <a href="../" class="text-decoration-none text-dark">
                    <span class="material-icons align-bottom">home</span> Inicio
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <span class="material-icons align-bottom">dashboard</span> Dashboard
            </li>
        </ol>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title"><span class="material-symbols-outlined align-bottom">finance_mode</span> Evolución de compras mensuales</h5>
                        <!-- <p class="card-text">Selecciona fecha y supermercado para registrar listas de cotizaciones</p> -->
                        <div>
                            <canvas id="myChart" style="width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title"><span class="material-symbols-outlined align-bottom">finance</span> Totales historicos acumulados por categorías</h5>
                        <!-- <p class="card-text">Selecciona fecha y supermercado para registrar listas de cotizaciones</p> -->
                        <div>
                            <canvas id="myChart_barras" style="width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-5">
                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title"><span class="material-symbols-outlined align-bottom">finance_mode</span> Evolución de compras mensuales</h5>
                        <div>
                            <canvas id="myChart_circular"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
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

        const ctx1 = document.getElementById('myChart').getContext('2d');
        fetch('grafico_linea.php') // tu archivo PHP
            .then(response => response.json())
            .then(data => {
                // Extraer labels y valores desde el JSON
                const labels = data.map(item => formatearLabelMesAnio(item.fecha_cotizacion));
                const valores = data.map(item => parsearMonto(item.total_mensual));

                // Agregar un espacio vacío al inicio
                labels.unshift(''); // etiqueta vacía
                valores.unshift(null); // valor nulo para que no dibuje punto

                // Generar un array de colores dinámico, uno por cada label
                const colores = labels.map((_, i) => {
                    const palette = [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ];
                    // Ciclar la paleta si hay más labels que colores
                    return palette[i % palette.length];
                });

                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Mensual',
                            data: valores,
                            borderWidth: 1,
                            borderColor: 'black',
                            backgroundColor: colores,
                            spanGaps: true // evita unir el null con el siguiente punto
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
            })
            .catch(error => console.error('Error:', error));
    </script>
    <script>
        const ctx = document.getElementById('myChart_barras').getContext('2d');
        fetch('grafico_circular.php') // tu archivo PHP
            .then(response => response.json())
            .then(data => {
                // Extraer labels y valores desde el JSON
                const labels = data.map(item => formatearLabelMesAnio(item.categorias));
                const valores = data.map(item => parsearMonto(item.total_mensual));

                // Generar un array de colores dinámico, uno por cada label
                const colores = labels.map((_, i) => {
                    const palette = [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)'
                    ];
                    // Ciclar la paleta si hay más labels que colores
                    return palette[i % palette.length];
                });


                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Suma por Categoría',
                            data: valores,
                            borderWidth: 1,
                            borderColor: 'black',
                            backgroundColor: colores,
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
                                anchor: 'end', // posición del label
                                align: 'top', // alineación sobre la barra
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
            })
            .catch(error => console.error('Error:', error));
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        const ctx2 = document.getElementById('myChart_circular').getContext('2d');
        fetch('grafico_circular.php')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => formatearLabelMesAnio(item.categorias));
                const valores = data.map(item => parsearMonto(item.total_mensual));

                // Generar colores dinámicos
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

                new Chart(ctx2, {
                    type: 'doughnut', // cambiamos a gráfico circular
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Suma por Categoría',
                            data: valores,
                            borderColor: 'white',
                            borderWidth: 2,
                            backgroundColor: colores
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const etiqueta = context.label ? context.label + ': ' : '';
                                        return etiqueta + formatearMonedaCLP(context.parsed);
                                    }
                                }
                            },
                            datalabels: {
                                formatter: (value, context) => formatearMonedaCLP(value),
                                color: 'black',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                position: 'right'
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            })
            .catch(error => console.error('Error:', error));
    </script>
</body>

</html>