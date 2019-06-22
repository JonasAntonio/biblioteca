<?php
require_once('TCPDF/tcpdf.php');
include_once('../../db/Conexao.php');
require_once('../../modelo/Usuario.php');
require_once('../../functions/functions.php');

function getNomeLivroExemplar($id_exemplar) {
    $sql = "
        SELECT 
            l.titulo
        FROM
            tb_livro AS l
                LEFT JOIN
            tb_exemplar AS e ON l.idtb_livro = e.tb_livro_id_tb_livro
        WHERE
            e.idtb_exemplar = :id
    "; 
    $statement = Conexao::getInstance()->prepare($sql);
    $statement->bindValue(":id", $id_exemplar);
    $statement->execute();
    $dados = $statement->fetch(PDO::FETCH_ASSOC);
    return $dados['titulo'];
}

function getNomeUsuarioId($id) {
    $sql = "SELECT nomeUsuario FROM tb_usuario WHERE idtb_usuario =  :id";
    $statement = Conexao::getInstance()->prepare($sql);
    $statement->bindValue(":id", $id);
    if ($statement->execute()) {
        $rs = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $rs[0]['nomeUsuario'];
    } else {
        throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
    }
}

function getNomeEditora($id) {
    $sql = "SELECT nomeEditora FROM tb_editora WHERE idtb_editora =  :id";
    $statement = Conexao::getInstance()->prepare($sql);
    $statement->bindValue(":id", $id);
    if ($statement->execute()) {
        $rs = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $rs[0]['nomeEditora'];
    } else {
        throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
    }
}

function getNomeCategoria($id) {
    $sql = "SELECT nomeCategoria FROM tb_categoria WHERE idtb_categoria =  :id";
    $statement = Conexao::getInstance()->prepare($sql);
    $statement->bindValue(":id", $id);
    if ($statement->execute()) {
        $rs = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $rs[0]['nomeCategoria'];
    } else {
        throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
    }
}

!empty($_GET['mode']) ? $mode = $_GET['mode'] : $mode = "";

switch ($mode) {
    case 'autores':
        $sql = "SELECT * FROM tb_autores";
        break;

    case 'editoras':
        $sql = "SELECT * FROM tb_editora";
        break;

    case 'categorias':
        $sql = "SELECT * FROM tb_categoria";
        break;

    case 'exemplares':
        $sql = "SELECT * FROM tb_exemplar AS e LEFT JOIN tb_livro AS l ON e.tb_livro_id_tb_livro = l.idtb_livro";
        break;

    case 'livros':
        $sql = "SELECT * FROM tb_livro";
        break;

    case 'emprestimos':
        $sql = "SELECT * FROM tb_emprestimo";
        break;

    case 'reservas':
        $sql = "SELECT * FROM tb_reserva";
        break;

    case 'usuarios':
        $sql = "SELECT * FROM tb_usuario";
        break;
    
    default:
        $sql = "";
        break;
}

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
$pdf->SetTitle($mode);
$pdf->SetSubject($mode);
$pdf->SetKeywords("$mode, PDF, biblioteca");

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();

$html = "
    <div class='col-md-6'>
    <p>Livros reservados nos últimos 3 meses</p>
    <img class='responsive-img' src='../../PHPlot/graficoLivroReservadoMes.php' />
    </div>
    <div class='col-md-6'>
    <p>Livros emprestados nos últimos 3 meses</p>
    <img class='responsive-img' src='../../PHPlot/graficoLivroEmprestadoMes.php' />
    </div>
";

$pdf->writeHTML($html);

$pdf->Output("$mode.pdf", 'I');