<?php
$_SESSION['active_window'] = "usuarios";

require_once "view/template.php";
require_once "dao/daoLivro.php";
require_once "modelo/Livro.php";
require_once "db/Conexao.php";

$dao = new daoLivro();

template::header();
template::sidebar();
template::mainpanel();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $titulo = (isset($_POST["titulo"]) && $_POST["titulo"] != null) ? $_POST["titulo"] : "";
    $isbn = (isset($_POST["isbn"]) && $_POST["isbn"] != null) ? $_POST["isbn"] : "";
    $edicao = (isset($_POST["edicao"]) && $_POST["edicao"] != null) ? $_POST["edicao"] : "";
    $ano = (isset($_POST["ano"]) && $_POST["ano"] != null) ? $_POST["ano"] : "";
    $editora = (isset($_POST["editora"]) && $_POST["editora"] != null) ? $_POST["editora"] : "";
    $categoria = (isset($_POST["categoria"]) && $_POST["categoria"] != null) ? $_POST["categoria"] : "";
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $titulo = null;
    $isbn = null;
    $edicao = null;
    $ano = null;
    $editora = null;
    $categoria = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $titulo != "" ) {
    $livro = new Livro($id, $titulo, $isbn, $ano, $edicao);
    $msg = $dao->salvar($livro);
    $id = null;
    $titulo = null;
    $isbn = null;
    $edicao = null;
    $ano = null;
    $editora = null;
    $categoria = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    $livro = new Livro($id, '', '', '', '');
    $resultado = $dao->atualizar($livro);
    $id = $resultado->getIdTbLivro();
    $titulo = $resultado->getTitulo();
    $isbn = $resultado->getISBN();
    $edicao = $resultado->getEdicao();
    $ano = $resultado->getAno();
    $editora = $resultado->getIdTbEditora();
    $categoria =$resultado->getIdTbCategoria();
}

$editoras = $dao->getAllEditoras();
$categorias = $dao->getAllCategorias();

?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Livros</h4>
                            <p class='category'>Lista de Livros do Sistema</p>
                        </div>
                        <div class='content table-responsive'>
                            <form action="?act=save&id=" method="POST" name="form1">

                                <input type="hidden" name="id" value="<?php
                                echo (!empty($id)) ? $id : '';
                                ?>"/>
                                <Label>Título</Label>
                                <input class="form-control" type="text" size="50" name="titulo" value="<?php
                                echo (!empty($titulo)) ? $titulo : '';
                                ?>" required/>
                                <Label>ISBN</Label>
                                <input class="form-control" type="text" size="50" name="isbn" value="<?php
                                echo (!empty($isbn)) ? $isbn : '';
                                ?>" required/>
                                <Label>Edição</Label>
                                <input class="form-control" type="text" size="50" name="edicao" value="<?php
                                echo (!empty($edicao)) ? $edicao : '';
                                ?>" required/>
                                <Label>Ano</Label>
                                <input class="form-control" type="text" size="50" name="ano" value="<?php
                                echo (!empty($ano)) ? $ano : '';
                                ?>" required/>
                                <Label>Editora</Label>
                                <select class="form-control" name="editora">
                                    <option value="">--Selecione--</option>
                                    <?php foreach ($editoras as $key=>$value) {?>
                                        <option value="<?=$value['idtb_editora']?>"><?=$value['nomeEditora']?></option>
                                    <?}?>
                                </select>
                                <Label>Categoria</Label>
                                <select class="form-control" name="categoria">
                                    <option value="">--Selecione--</option>
                                    <?php foreach ($categorias as $key=>$value) {?>
                                        <option value="<?=$value['idtb_categoria']?>"><?=$value['nomeCategoria']?></option>
                                    <?}?>
                                </select>
                                <br/>
                                <input class="btn btn-success" type="submit" value="REGISTRAR">
<!--                                <input class="btn btn-success" type="button" onclick='document.location="pdf/tcpdf/relatorio.php"' value="EXPORTAR">-->
                                <hr>
                            </form>
                            <?php
                            echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                            //chamada a paginação
                            $dao->tabelapaginada();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
template::footer();
?>