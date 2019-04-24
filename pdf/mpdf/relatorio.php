<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 05/01/2018
 * Time: 18:00
 */

//So funciona se desativar os erros!
ini_set('display_errors', 0);
include_once('../../db/Conexao.php');

$sql = "SELECT * FROM tb_autores";
$statement = Conexao::getInstance()->prepare($sql);
$statement->execute();
$dados = $statement->fetchAll(PDO::FETCH_ASSOC);

include("mpdf/mpdf.php");

$html = '
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>NOME</th>
        </tr>
    </thead>    
    <tbody>';
foreach ($dados as $key => $value) {
//    print_r($value);
    $html.= '
        <tr>
            <td>'.$value['idtb_autores'].'</td>
            <td>'.$value['nomeAutor'].'</td>
        </tr>
    ';
}
$html.='
    </tbody>
</table>
';

$mpdf=new mPDF();
$mpdf->SetCreator(PDF_CREATOR);
$mpdf->SetAuthor('Jonas Vicente');
$mpdf->SetTitle('Autores');
$mpdf->SetSubject('Autores');
$mpdf->SetKeywords('Autores, PDF, biblioteca');
$mpdf->SetDisplayMode('fullpage');
$mpdf->nbpgPrefix = ' de ';
$mpdf->setFooter('Página {PAGENO}{nbpg}');
$mpdf->WriteHTML($html);
$mpdf->Output('autores.pdf','I');

exit;