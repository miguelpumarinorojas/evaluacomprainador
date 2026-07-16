    <?php

    function formatoFechaDMY($fecha)
    {
        $fechaObj = DateTime::createFromFormat('Y-m-d', (string) $fecha);
        return $fechaObj instanceof DateTime ? $fechaObj->format('d-m-Y') : $fecha;
    }

    function formatoMonedaCLP($valor)
    {
        return '$ ' . number_format((float) $valor, 0, ',', '.');
    }


    function formatoFechaHoraDMY($fecha)
    {
        $fechaObj = DateTime::createFromFormat('Y-m-d H:i:s', (string) $fecha);
        return $fechaObj instanceof DateTime ? $fechaObj->format('d-m-Y H:i:s') : $fecha;
    }


    function formatoMesAño($fecha)
    {
        $fechaObj = DateTime::createFromFormat('Y-m', (string) $fecha);

        if (!($fechaObj instanceof DateTime)) {
            return $fecha;
        }

        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $mes = (int) $fechaObj->format('n');
        $anio = $fechaObj->format('Y');

        return (isset($meses[$mes]) ? $meses[$mes] : $fechaObj->format('m')) . '-' . $anio;
    }


