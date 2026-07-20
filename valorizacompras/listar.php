<?php

require('../complementos/FPDF/fpdf.php');
include("../inc/funciones.php");


$mes_compra = '';
if (isset($_GET['mes_compra'])) {
    $mes_compra = $_GET['mes_compra'];
} elseif (isset($_POST['mes_compra'])) {
    $mes_compra = $_POST['mes_compra'];
}

class PDF extends FPDF
{
   public $mes_compra; // propiedad para guardar el mes
   public $mes_compra_query; // propiedad para guardar el mes para la consulta SQL
   public $fecha_impresion;

   // Constructor extendido
   function __construct($orientation='P', $unit='mm', $size='A4', $mes_compra='')
   {
      parent::__construct($orientation, $unit, $size);
      $this->mes_compra = formatoMesAño($mes_compra);
      $this->mes_compra_query = $mes_compra; // Guardar el mes para la consulta SQL
      date_default_timezone_set('America/Santiago');
      $this->fecha_impresion = date('d/m/Y H:i');
   }

   // Cabecera de página
   function Header()
   {
      $fuente = 'Arial';

      $this->SetFont($fuente, 'B', 19);
      $this->SetTextColor(0, 0, 0);

      // Aquí usamos la propiedad $mes_compra
      $titulo = "Lista de compras " . utf8_decode($this->mes_compra);
      $this->Cell(0, 10, $titulo, 0, 1, 'L', 0);

      $this->Ln(5);
   }

   function Footer()
   {
      $this->SetY(-12);
      $this->SetFont('Arial', 'I', 8);
      $this->Cell(0, 5, 'Impreso el ' . $this->fecha_impresion, 0, 0, 'L');
      $this->Cell(0, 5, 'Pagina ' . $this->PageNo() . '/{total_pages}', 0, 0, 'R');
   }

   private function renderCabeceraTabla(array $header, array $colWidths)
   {
      $this->SetFont('Arial', 'B', 9);
      $this->SetFillColor(200, 200, 200);

      foreach ($header as $i => $col) {
         $this->Cell($colWidths[$i], 10, utf8_decode($col), 1, 0, 'C', true);
      }

      $this->Ln();
   }

   private function renderFilaTabla(array $values, array $colWidths)
   {
      $this->SetFont('Arial', '', 9);

      foreach ($values as $i => $val) {
         $this->Cell($colWidths[$i], 7, utf8_decode((string) $val), 1, 0, 'C');
      }

      $this->Ln();
   }

   private function renderBloqueSupermercado($descripcionSupermercado, array $rows)
   {
      $this->AddPage();
      $this->SetFont('Arial', 'B', 12);
      $this->Cell(0, 8, 'Supermercado: ' . utf8_decode($descripcionSupermercado), 0, 1, 'L');
      $this->Ln(2);

      $header = array('N', 'Producto', 'Marca', 'Cap.', 'UM', 'Cantidad', 'Precio Unit.', 'Precio x UM', 'Total'); //
      $colWidths = array(10, 42, 26, 14, 14, 20, 24, 20, 20); // Ajusta los anchos según tus necesidades

      $this->renderCabeceraTabla($header, $colWidths);

      $numero = 1;
      foreach ($rows as $row) {
         $values = array(
            $numero++,
            $row['descripcion_producto'],
            $row['descripcion_marca'],
            $row['capacidad'],
            $row['descripcion_um'],
            $row['cantidad_compra'],
            formatoMonedaCLP($row['precio']),
            formatoMonedaCLP($row['menor_precio_por_um']),
            formatoMonedaCLP($row['total_por_producto'])
         );

         $this->renderFilaTabla($values, $colWidths);
      }
   }

   //cuerpo
   function ChapterBody()
   {

      $servidor = "localhost";
      $usuario = "root";
      $clave = "";
      $baseDeDatos = "evaluacomprainador";

      // Conexión a la base de datos
      $conexion = new mysqli($servidor, $usuario, $clave, $baseDeDatos);
      if ($conexion->connect_error) {
         die("Error de conexión: " . $conexion->connect_error);
      }

      // Consulta SQL
      $sql = "SELECT t1.id,
               t3.logo AS logo_supermercado,
               t3.descripcion AS descripcion_supermercado,
               t2.descripcion AS descripcion_producto,
               t4.descripcion AS descripcion_um,
               t6.descripcion AS descripcion_marca,
               t1.capacidad,
               t1.precio,
               t1.menor_precio_por_um,
               t1.cantidad_compra,
               t1.total_por_producto
        FROM lista_compras t1
        INNER JOIN productos t2 ON t1.producto = t2.id
        INNER JOIN supermercados t3 ON t1.supermercado = t3.id
        INNER JOIN unidades t4 ON t1.um = t4.id
        INNER JOIN categorias t5 ON t2.categoria = t5.id
        INNER JOIN marcas t6 ON t1.marca = t6.id
        WHERE t1.estado = 0 and t1.fecha_cotizacion = '" . $this->mes_compra_query . "'
        ORDER BY t3.descripcion, t5.descripcion, t2.descripcion";

      $resultado = $conexion->query($sql);

      $grupos = array();
      while ($row = $resultado->fetch_assoc()) {
         $descripcionSupermercado = $row['descripcion_supermercado'];
         if (!isset($grupos[$descripcionSupermercado])) {
            $grupos[$descripcionSupermercado] = array();
         }

         $grupos[$descripcionSupermercado][] = $row;
      }

      if (empty($grupos)) {
         $this->AddPage();
         $this->SetFont('Arial', '', 11);
         $this->Cell(0, 10, 'No se encontraron productos para el mes seleccionado.', 0, 1, 'L');
         return;
      }

      foreach ($grupos as $descripcionSupermercado => $rows) {
         $this->renderBloqueSupermercado($descripcionSupermercado, $rows);
      }
   }
}


$pdf = new PDF('P','mm','A4',$mes_compra);
$pdf->ChapterBody();
$pdf->AliasNbPages('{total_pages}'); // muestra la pagina / y total de paginas
$pdf->Output('ListadeCompras'.'_'.$mes_compra.'.pdf', 'D');//nombreDescarga, Visor(I->visualizar - D->descargar)
