<?php

require('./fpdf.php');

class PDF extends FPDF
{

   // Cabecera de página
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
      $fontFamily = 'Arial';
      $fontSizeBody = 12;
      $fontSizeSignature = 14;

      /* Cuerpo del contrato */
      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('Entre Levelup fitness chile, en adelante denominado "El Gimnasio", representado por '), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('Darnich Esteban Padilla Jara, RUT 17.790.143-1, con domicilio en Los Corchos 2987,'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('Quilpué, y el cliente ____________________________________________________,'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('RUT____________________, en adelante denominado "El Cliente", con domicilio en '), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('__________________________________________________________se establece '), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('el siguiente contrato de prestación de servicios:'), 0, 0, '', 0);
      $this->Ln(8);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('1.	Objeto del Contrato: El Gimnasio se compromete a proporcionar al Cliente acceso '), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('y uso de sus instalaciones y servicios relacionados con el ejercicio físico, con el fin'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('de promover la salud y el bienestar del Cliente.'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('2.	Duración del Contrato: Este contrato tendrá una duración "INDEFINIDA" a partir del'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('_____________________(FECHA DE INSCRIPCION).'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('3.	Condiciones de Uso: El Cliente declara que está en condiciones de realizar ejercicio'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('físico y que no padece ninguna enfermedad o lesión que le impida realizar actividad'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('física. En caso de ser menor de edad, el Cliente deberá presentar una autorización'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('simple firmada por su apoderado legal.'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('4.	Cuotas y Pagos: El Cliente se compromete a abonar las cuotas mensuales, trimes-'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('trales, semestrales o anuales correspondientes al uso de las instalaciones del, '), 0, 0, '', 0);
      $this->Ln(5);    

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('Gimnasio según las tarifas establecidas y acordadas al momento de la firma de este'), 0, 0, '', 0);
      $this->Ln(5);  

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('contrato.'), 0, 0, '', 0);
      $this->Ln(5);  
  
      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('5.	Uso de Instalaciones: El Cliente se compromete a hacer uso adecuado de las instala-'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('ciones y equipos del Gimnasio, siguiendo las indicaciones del personal capacitado y'), 0, 0, '', 0);
      $this->Ln(5);  

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('respetando las normas de convivencia y seguridad.'), 0, 0, '', 0);
      $this->Ln(5);  

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('6.	Responsabilidad: El Gimnasio no se hace responsable de cualquier lesión o accidente '), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('que pueda sufrir el Cliente durante el uso de las instalaciones, salvo que sea producto'), 0, 0, '', 0);
      $this->Ln(5);  

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('de una negligencia comprobada por parte del Gimnasio.'), 0, 0, '', 0);
      $this->Ln(5);  

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('7.	Modificaciones: Cualquier modificación o adición a este contrato deberá ser acordada'), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('por ambas partes por escrito.'), 0, 0, '', 0);
      $this->Ln(5);  

      $this->Cell(20);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('8.	Firma: Ambas partes declaran haber leído, entendido y aceptado los términos y '), 0, 0, '', 0);
      $this->Ln(5);

      $this->Cell(25);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeBody);
      $this->Cell(85, 10, utf8_decode('condiciones establecidos en este contrato.'), 0, 0, '', 0);
      $this->Ln(10);       
 //firmas
      $this->Cell(10);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeSignature);
      $this->Cell(85, 10, utf8_decode('Firma del Representante Legal del Gimnasio:_________________________ '), 0, 0, '', 0);
      $this->Ln(10);

      $this->Cell(10);  // mover a la derecha
      $this->SetFont('Arial', '', $fontSizeSignature);
      $this->Cell(85, 10, utf8_decode('Firma del Cliente:____________________________ '), 0, 0, '', 0);
      $this->Ln(5);
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
   }
}

$pdf = new PDF();
$pdf->AddPage(); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->ChapterBody();
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas



$pdf->Output('ContratoLevelUp2024.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)
