<?php
require('./fpdf.php');

class PDF extends FPDF
{
function Header()
{
    $this->Image('levelupHeader.png', 10, 5, 50); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
    $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
    $this->Cell(45); // Movernos a la derecha
    $this->SetTextColor(0, 0, 0); //color
 
    /* Titulo del contrato */
    //color
    $this->Ln(15); // Salto de línea
    // $this->SetTextColor(228, 100, 0);
    $this->Cell(50); // mover a la derecha
    $this->SetFont('Arial', 'B', 15);
    $this->Cell(100, 10, utf8_decode("Contrato de Prestación de Servicios para el Uso de"), 0, 1, 'C', 0);
    $this->Cell(200, 5, utf8_decode("Instalaciones del Gimnasio"), 0, 1, 'C', 0);
    $this->Ln(5);
}


function ChapterBody()
{
    // Leemos el fichero
    $txt = file_get_contents('contrato.txt');
    // Times 12
    $this->SetFont('Arial','',14);
    // Imprimimos el texto justificado
    $this->MultiCell(0,5,utf8_decode($txt));
    // Salto de línea
    $this->Ln();
}

function PrintChapter($num, $title)
{
    $this->AddPage();
    // $this->ChapterTitle($num,$title);
    $this->ChapterBody();
}
}

$pdf = new PDF();
$title = 'Contrato de Prestación de Servicios para el Uso de Instalaciones del Gimnasio';
$pdf->SetTitle($title);
$pdf->PrintChapter(1,'UN RIZO DE HUIDA',utf8_decode('contrato.txt'));
$pdf->Output();
?>