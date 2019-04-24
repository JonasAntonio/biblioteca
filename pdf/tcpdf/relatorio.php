<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 05/01/2018
 * Time: 18:56
 */

//So funciona se desativar os erros!
//ini_set('display_errors', 0);
require_once('TCPDF/tcpdf.php');
include_once('../../db/Conexao.php');
require_once('../../modelo/Usuario.php');

$sql = "SELECT * FROM tb_usuario";
$statement = Conexao::getInstance()->prepare($sql);
$statement->execute();
$dados = $statement->fetchAll(PDO::FETCH_ASSOC);

class MYPDF extends TCPDF {

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF();

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Jonas Vicente');
$pdf->SetTitle('Usuários');
$pdf->SetSubject('Usuários');
$pdf->SetKeywords('Usuários, PDF, biblioteca');

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();

$html = '
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>NOME</th>
            <th>TIPO</th>
            <th>EMAIL</th>
        </tr>
    </thead>    
    <tbody>';
foreach ($dados as $key => $value) {
    $tipo = Usuario::getTipoText($value['tipo']);
    $html.= '
        <tr>
            <td>'.$value['idtb_usuario'].'</td>
            <td>'.$value['nomeUsuario'].'</td>
            <td>'.$tipo.'</td>
            <td>'.$value['email'].'</td>
        </tr>
    ';
}
$html.='
    </tbody>
</table>
';

$pdf->writeHTML($html);

$pdf->Output('usuarios.pdf', 'I');