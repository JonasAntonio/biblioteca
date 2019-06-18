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

switch ($mode) {
    case 'autores':
        $html = '
        <table>
            <tr>
                <td>AUTORES</td>
            </tr>
        </table>
        <table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="lightgray">
                    <th>ID</th>
                    <th>NOME</th>
                </tr>
            </thead>    
            <tbody>';
        foreach ($dados as $key => $value) {
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
        <br><br>
        ';
    break;

    case 'editoras':
        $html = '
        <table>
            <tr>
                <td>EDITORAS</td>
            </tr>
        </table>
        <table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="lightgray">
                    <th>ID</th>
                    <th>NOME</th>
                </tr>
            </thead>    
            <tbody>';
        foreach ($dados as $key => $value) {
            $html.= '
                <tr>
                    <td>'.$value['idtb_editora'].'</td>
                    <td>'.$value['nomeEditora'].'</td>
                </tr>
            ';
        }
        $html.='
            </tbody>
        </table>
        <br><br>
        ';
        break;

    case 'categorias':
        $html = '
        <table>
            <tr>
                <td>CATEGORIAS</td>
            </tr>
        </table>
        <table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="lightgray">
                    <th>ID</th>
                    <th>NOME</th>
                </tr>
            </thead>    
            <tbody>';
        foreach ($dados as $key => $value) {
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
        <br><br>
        ';
        break;

    case 'exemplares':
        $html = '
        <table>
            <tr>
                <td>EXEMPLARES</td>
            </tr>
        </table>
        <table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="lightgray">
                    <th>ID</th>
                    <th>TIPO</th>
                    <th>TÍTULO DO LIVRO</th>
                    <th>EMPRESTADO</th>
                    <th>RESERVADO</th>
                </tr>
            </thead>    
            <tbody>';
        foreach ($dados as $key => $value) {
            ($value['tipoExemplar'] == 0) ? $tipo = 'CIRCULAR' : $tipo = 'NÃO CIRCULAR';
            ($value['emprestado'] == 'S') ? $emprestado = 'SIM' : $emprestado = 'NÃO';
            ($value['reservado'] == 'S') ? $reservado = 'SIM' : $reservado = 'NÃO';
            $html.= '
                <tr>
                    <td>'.$value['idtb_exemplar'].'</td>
                    <td>'.$tipo.'</td>
                    <td>'.$value['titulo'].'</td>
                    <td>'.$emprestado.'</td>
                    <td>'.$reservado.'</td>
                </tr>
            ';
        }
        $html.='
            </tbody>
        </table>
        <br><br>
        ';
        break;

    case 'livros':
        $html = '
        <table>
            <tr>
                <td>LIVROS</td>
            </tr>
        </table>
        <table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="lightgray">
                    <th>ID</th>
                    <th>TÍTULO</th>
                    <th>ISBN</th>
                    <th>EDIÇÃO</th>
                    <th>ANO</th>
                    <th>EDITORA</th>
                    <th>CATEGORIA</th>
                </tr>
            </thead>    
            <tbody>';
        foreach ($dados as $key => $value) {
            $html.= '
                <tr>
                    <td>'.$value['idtb_livro'].'</td>
                    <td>'.$value['titulo'].'</td>
                    <td>'.$value['isbn'].'</td>
                    <td>'.$value['edicao'].'</td>
                    <td>'.$value['ano'].'</td>
                    <td>'.getNomeEditora($value['tb_editora_id_tb_editora']).'</td>
                    <td>'.getNomeCategoria($value['tb_categoria_id_tb_categoria']).'</td>
                </tr>
            ';
        }
        $html.='
            </tbody>
        </table>
        <br><br>
        ';
        break;

    case 'emprestimos':
        $html = '
        <table>
            <tr>
                <td>EMPRÉSTIMOS</td>
            </tr>
        </table>
        <table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="lightgray">
                    <th>ID</th>
                    <th>USUÁRIO</th>
                    <th>DATA DE EMPRÉSTIMO</th>
                    <th>DATA DE VENCIMENTO</th>
                    <th>DATA DE DEVOLUÇÃO</th>
                    <th>EXEMPLARES</th>
                    <th>OBSERVAÇÃO</th>
                </tr>
            </thead>    
            <tbody>';
        foreach ($dados as $key => $value) {
            $sql = "SELECT * FROM tb_emprestimo_usuario WHERE id_emprestimo = :id_emprestimo";
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue('id_emprestimo', $value['id_emprestimo']);
            $statement->execute();
            $exemplaresEmprestados = $statement->fetchAll(PDO::FETCH_ASSOC);
        
            $html.= '
                <tr>
                    <td>'.$value['id_emprestimo'].'</td>
                    <td>'.getNomeUsuarioId($value['id_usuario']).'</td>
                    <td>'.formatarData($value['dataEmprestimo']).'</td>
                    <td>'.formatarData($value['dataVencimento']).'</td>
                    <td>'.formatarData($value['dataDevolucao']).'</td>
                    <td>';
                    foreach ($exemplaresEmprestados as $exemplar) {
                        $html.= getNomeLivroExemplar($exemplar['id_exemplar']);
                    }
                    $html.='</td>
                    <td>'.$value['observacao'].'</td>
                </tr>
            ';
        }
        $html.='
            </tbody>
        </table>
        <br><br>
        ';
        break;

    case 'reservas':
        $html = '
        <table>
            <tr>
                <td>RESERVAS</td>
            </tr>
        </table>
        <table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="lightgray">
                    <th>ID</th>
                    <th>USUÁRIO</th>
                    <th>DATA DE RESERVA</th>
                    <th>DATA DE VENCIMENTO</th>
                    <th>OBSERVAÇÃO</th>
                    <th>EXEMPLARES</th>
                    <th>STATUS</th>
                </tr>
            </thead>    
            <tbody>';
        foreach ($dados as $key => $value) {
            $sql = "SELECT * FROM tb_reserva_usuario WHERE id_reserva = :id_reserva";
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue('id_reserva', $value['id_reserva']);
            $statement->execute();
            $exemplaresReservados = $statement->fetchAll(PDO::FETCH_ASSOC);
        
            switch ($value['status']) {
                case 'E':
                    $status = 'EMPRESTADO';
                    break;
        
                case 'C':
                    $status = 'CANCELADO';
                    break;
        
                case 'R':
                    $status = 'RESERVADO';
                    break;
                
                default:
                    # code...
                    break;
            }
        
            $html.= '
                <tr>
                    <td>'.$value['id_reserva'].'</td>
                    <td>'.getNomeUsuarioId($value['id_usuario']).'</td>
                    <td>'.formatarData($value['dataReserva']).'</td>
                    <td>'.formatarData($value['dataVencimento']).'</td>
                    <td>'.$value['observacao'].'</td>
                    <td>';
                    foreach ($exemplaresReservados as $exemplar) {
                        $html.= getNomeLivroExemplar($exemplar['id_exemplar']);
                    }
                    $html.='</td>
                    <td>'.$status.'</td>
                </tr>
            ';
        }
        $html.='
            </tbody>
        </table>
        <br><br>
        ';
        break;

    case 'usuarios':
        $html = '
        <table>
            <tr>
                <td>USUÁRIOS</td>
            </tr>
        </table>
        <table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="lightgray">
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
        <br><br>
        ';
        break;

    default:
        $html = "";
        break;
}
$pdf->writeHTML($html);

$pdf->Output("$mode.pdf", 'I');