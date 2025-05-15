<?php
require_once('tcpdf.php');
class MyReporte extends TCPDF
{
    public function Header()
    {
        $this->SetFont('helvetica', 'B', 18);
        $this->cell(25,10,'',0,0, 'R', $this->Image(ROOT.'/libs/images/logolinea.png',20,13,30));
         $this->Cell(150,40, 'Línea Digital Del Sureste',0,0,'R');
        // Logo
    //    $image_file = ROOT.'libs/images/logolinea.png';
        //$image_file = K_PATH_IMAGES.'logolinea.png';
       // $this->Image($image_file, 10, 15, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Agregar fuente
        
        // T+itulo
        //$this->Cell(0, 10, 'Línea Digital Del Sureste', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        
        $this->Line(10,25,206,25);
        $this->setFont('helvetica', 'B', 8);
        $datos = "1a Norte Poniente #834, Tuxtla Gutiérrez, 29000 | (961) 6189200 | " . date("Y-m-d H:i:s");
        $this->Cell(0,55, $datos, 0,0,'R');
        $this->Ln(30);
        $this->setFont('helvetica', 'B', 12);
        $this->Cell(60,25, 'H o j a   d e  R e s g u a r d o.');
    }
    public function Footer()
    {
        $this->SetY(-15);
        $this->setFont('helvetica', 'B', 8);
        $this->cell(0,10, 'Línea Digital | Evolucionando Contigo','T',0, 'R');
    }
}