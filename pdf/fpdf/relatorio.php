<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 05/01/2018
 * Time: 18:00
 */

require('fpdf181/fpdf.php');
include_once('../../db/Conexao.php');

$sql = "SELECT idtb_categoria, nomeCategoria FROM tb_categoria";
$statement = Conexao::getInstance()->prepare($sql);
$statement->execute();
$dados = $statement->fetchAll(PDO::FETCH_ASSOC);

$html = '
<table>
    <thead>
        <th>
            <td>ID</td>
            <td>NOME</td>
        </th>
    </thead>    
    <tbody>';
foreach ($dados as $key => $value) {
//    print_r($value);
    $html.= '
        <tr>
            <td>'.$value['idtb_categoria'].'</td>
            <td>'.$value['nomeCategoria'].'</td>
        </tr>
    ';
}
$html.='
    </tbody>
</table>
';

class PDF extends FPDF
{
    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';

    function Footer()
    {
        //Vai para 1.5 cm da parte inferior
        $this->SetY(-15);
        //Seleciona a fonte Arial itálico 8
        $this->SetFont('Arial','I',8);
        //Imprime o número da página corrente e o total de páginas
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extract attributes
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        // Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',20);
$pdf->SetFontSize(14);
$pdf->SetLeftMargin(45);
//$pdf->Image('logo.png',10,12,30,0,'','http://www.fpdf.org');
$pdf->WriteHTML($html);
$pdf->AliasNbPages();
$pdf->Output();
?>