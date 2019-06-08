<?php

require_once "view/template.php";
require_once "dao/daoExemplar.php";
require_once "modelo/Exemplar.php";
require_once "db/Conexao.php";
require_once "dao/daoLivro.php";

$daoLivro = new daoLivro();
$dao = new daoExemplar();

template::header();
$_SESSION['active_window'] = "exemplares";
template::sidebar();
template::mainpanel();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $tipoExemplar = (isset($_POST["tipoExemplar"]) && $_POST["tipoExemplar"] != null) ? $_POST["tipoExemplar"] : "";
    $id_tb_livro = (isset($_POST["livro"]) && $_POST["livro"] != null) ? $_POST["livro"] : "";
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $tipoExemplar = null;
    $id_tb_livro = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" ) {
    $exemplar = new Exemplar($id, $tipoExemplar, $id_tb_livro);
    $msg = $dao->salvar($exemplar);
    $id = null;
    $tipoExemplar = null;
    $id_tb_livro = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    $exemplar = new Exemplar($id, '', '', '', '');
    $resultado = $dao->atualizar($exemplar);
    $id = $resultado->getIdTbExemplar();
    $tipoExemplar = $resultado->getTipoExemplar();
    $id_tb_livro = $resultado->getTbLivroIdTbLivro();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    $exemplar = new Exemplar($id, "", "", "", "");
    $msg = $dao->remover($exemplar);
    $id = null;
}

?>

<div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Exemplares</h4>
                            <p class='category'>Lista de Exemplares</p>
                        </div>
                        <div class='content table-responsive'>
                            <form action="?act=save&id=" method="POST" name="form1">
                                <input type="hidden" name="id" value="<?= (!empty($id)) ? $id : ''?>"/>
                                <Label>Tipo de Exemplar</Label>
                                <select class="form-control" name="tipoExemplar">
                                    <option value="">--Selecione--</option>
                                    <option value="0" <?=$tipoExemplar == 0 ? 'selected' : ''?>>Circular</option>
                                    <option value="1" <?=$tipoExemplar == 1 ? 'selected' : ''?>>Não Circular</option>
                                </select>
                                <Label>Livro</Label>
                                <select class="form-control" name="livro">
                                    <option value="">--Selecione--</option>
                                    <?php foreach ($daoLivro->listAll() as $key => $value) {?>
                                        <option value="<?=$value['idtb_livro']?>" <?=$value['idtb_livro'] == $id_tb_livro ? 'selected' : ''?>><?=$value['titulo']?></option>
                                    <?php } ?>
                                </select>
                                <br/>
                                <input class="btn btn-success" type="submit" value="REGISTRAR">
                                <!-- <input class="btn btn-success" type="button" onclick='document.location="pdf/tcpdf/relatorio.php"' value="EXPORTAR"> -->
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