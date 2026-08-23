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
   public $header;
   public $colWidths;

   // Constructor extendido
   function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $mes_compra = '')
   {
      parent::__construct($orientation, $unit, $size);
      $this->mes_compra = formatoMesAño($mes_compra);
      date_default_timezone_set('America/Santiago');
      $this->fecha_impresion = date('d/m/Y H:i');

      // Definir cabecera y anchos de columnas
      $this->header = array('N°', 'Producto', 'Categoría', 'Cantidad');
      $this->colWidths = array(10, 100, 40, 30);
   }

   // Cabecera de página
   function Header()
   {
      $this->SetFont('Arial', 'B', 14);
      $this->Cell(0, 10, "Lista de compras " . utf8_decode($this->mes_compra), 0, 1, 'L');
      $this->Ln(3);

      // Dibujar cabecera de tabla
      $this->SetFont('Arial', 'B', 9);
      $this->SetFillColor(200, 200, 200);
      foreach ($this->header as $i => $col) {
         $this->Cell($this->colWidths[$i], 10, utf8_decode($col), 1, 0, 'C', true);
      }
      $this->Ln();
   }

   function Footer()
   {
      $this->SetY(-12);
      $this->SetFont('Arial', 'I', 8);
      $this->Cell(0, 5, 'Impreso el ' . $this->fecha_impresion, 0, 0, 'L');
      $this->Cell(0, 5, 'Página ' . $this->PageNo() . '/{total_pages}', 0, 0, 'R');
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

   private function renderFilaTabla(array $values)
   {
      $this->SetFont('Arial', '', 9);
      foreach ($values as $i => $val) {
         $this->Cell($this->colWidths[$i], 7, utf8_decode((string) $val), 1, 0, 'C');
      }
      $this->Ln();
   }

   //cuerpo
   function ChapterBody($mes_compra)
   {

   include("../inc/connection.php");

      // $servidor = "localhost";
      // $usuario = "root";
      // $clave = "";
      // $baseDeDatos = "evaluacomprainador";

      // $conexion = new mysqli($servidor, $usuario, $clave, $baseDeDatos);
      // if ($conn->connect_error) {
      //    die("Error de conexión: " . $conn->connect_error);
      // }

      // Consulta SQL
      $query_1 = "SELECT * FROM lista_compras_mensual WHERE mes_compra = '$mes_compra'";
      $result_1 = $conn->query($query_1);

      if ($result_1->num_rows > 0) {
         $query = "SELECT 
                        p.id,
                        p.descripcion,
                        c.icono,
                        c.descripcion AS descripcion_categoria,
                        IFNULL(l.cantidad, '') AS cantidad,
                        IFNULL(l.estado, '') AS estado
                    FROM productos p
                    INNER JOIN categorias c ON p.categoria = c.id
                    LEFT JOIN lista_compras_mensual l 
                        ON p.id = l.producto 
                        AND l.mes_compra = '$mes_compra'
                    WHERE p.estado = 1
                    ORDER BY c.descripcion, p.descripcion;";
      } else {
         $query = "SELECT DISTINCT t1.id, t1.descripcion,t3.icono,t3.descripcion as descripcion_categoria, '' as cantidad, '' as estado
                    FROM productos t1 
                    INNER JOIN categorias t3 on t1.categoria = t3.id
                    WHERE t1.estado = 1
                    ORDER BY t3.descripcion, t1.descripcion";
      }

      $resultado = $conn->query($query);
      $this->AddPage();

      if ($resultado->num_rows == 0) {
         $this->SetFont('Arial', '', 11);
         $this->Cell(0, 10, 'No se encontraron productos para el mes seleccionado.', 0, 1, 'L');
         return;
      }

      $numero = 1;
      while ($row = $resultado->fetch_assoc()) {
         $values = array(
            $numero++,
            $row['descripcion'],
            $row['descripcion_categoria'],
            $row['cantidad']
         );
         $this->renderFilaTabla($values);
      }
   }
}


$pdf = new PDF('P', 'mm', 'A4', $mes_compra);
$pdf->ChapterBody($mes_compra);
$pdf->AliasNbPages('{total_pages}'); // muestra la pagina / y total de paginas
$pdf->Output('ListadeCompras' . '_' . $mes_compra . '.pdf', 'D');//nombreDescarga, Visor(I->visualizar - D->descargar)
