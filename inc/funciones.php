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
