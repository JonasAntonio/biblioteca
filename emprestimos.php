<?php

require_once "view/template.php";
require_once "dao/daoEmprestimo.php";
require_once "dao/daoExemplar.php";
require_once "dao/daoUser.php";
require_once "modelo/Emprestimo.php";
require_once "modelo/Usuario.php";
require_once "modelo/Exemplar.php";
require_once "db/Conexao.php";

$dao = new daoEmprestimo();
$daoUser = new daoUser();
$daoExemplar = new daoExemplar();

template::header();
$_SESSION['active_window'] = "emprestimos";
template::sidebar();
template::mainpanel();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = (isset($_POST["id_usuario"]) && $_POST["id_usuario"] != null) ? $_POST["id_usuario"] : "";
    $exemplares = (isset($_POST["exemplares"]) && $_POST["exemplares"] != null) ? $_POST["exemplares"] : "";
    $dataEmprestimo = date('Y-m-d');
    $dataDevolucao = (isset($_POST["dataDevolucao"]) && $_POST["dataDevolucao"] != null) ? $_POST["dataDevolucao"] : "";
    $observacao = (isset($_POST["observacao"]) && $_POST["observacao"] != null) ? $_POST["observacao"] : "";
} else if (!isset($id_usuario)) {
    // Se não se não foi setado nenhum valor para variável $id_usuario
    // $id_usuario = (isset($_GET["id_usuario"]) && $_GET["id_usuario"] != null) ? $_GET["id_usuario"] : "";
    // $id_usuario = NULL;
    $exemplares = NULL;
    $dataEmprestimo = NULL;
    $dataDevolucao = NULL;
    $observacao = NULL;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" ) {
    $emprestimo = new Emprestimo($id_usuario, $exemplares, $dataEmprestimo, $dataDevolucao, $observacao);
    $msg = $dao->salvarEmprestimo($emprestimo);
    $id_usuario = NULL;
    $exemplares = NULL;
    $dataEmprestimo = NULL;
    $dataDevolucao = NULL;
    $observacao = NULL;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id_usuario != "") {
    $emprestimo = new Emprestimo($id_usuario, '', '', '', '', '');
    $resultado = $dao->atualizar($emprestimo);
    $id_usuario = $resultado->getIdUsuario();
    $exemplares = $resultado->getIdExemplar();
    $dataEmprestimo = $resultado->getDataEmprestimo();
    $dataDevolucao = $resultado->getDataDevolucao();
    $observacao = $resultado->getObservacao();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id_usuario != "") {
    $emprestimo = new Emprestimo($id_usuario, "", "", "", "", "");
    $msg = $dao->remover($emprestimo);
    $id_usuario = null;
}

?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Empréstimos</h4>
                            <p class='category'>Lista de Empréstimos do Sistema</p>
                        </div>
                        <div class='content table-responsive'>
                            <form action="?act=save&id_usuario=" method="POST" name="form1">
                                <input type="hidden" name="id_usuario" value="<?php
                                // Preenche o id_usuario no campo id_usuario com um valor "value"
                                echo (!empty($id_usuario)) ? $id_usuario : '';
                                ?>"/>
                                <Label for="usuario">Usuário</Label>
                                <select class="form-control" name="id_usuario">
                                    <option value="">--Selecione--</option>
                                    <? foreach ($daoUser->listAll() as $value) {?>
                                        <option value="<?=$value['idtb_usuario']?>"
                                            <?php if(!empty($id_usuario)) echo $id_usuario == $value['idtb_usuario'] ? 'selected' : ''?>>
                                            <?=$value['nomeUsuario']?>
                                        </option>
                                    <?}?>
                                </select>
                                <Label>Exemplares</Label>
                                <select class="form-control" name="exemplares[]" multiple>
                                    <option value="">--Selecione--</option>
                                    <? foreach ($daoExemplar->listAll() as $value) {
                                        if(!$dao->exemplarExiste($value['idtb_exemplar'])) {?>
                                        <option value="<?=$value['idtb_exemplar']?>"
                                            <?php if(!empty($tipo)) echo $tipo == $value['idtb_exemplar'] ? 'selected' : ''?>>
                                            <?=$value['titulo']?>
                                        </option>
                                        <? }
                                    } ?>
                                </select>
                                <label for="dataDevolucao">Data de Devolução</label>
                                <input type="date" name="dataDevolucao" id="dataDevolucao" class="form-control">
                                <label for="observacao">Observação</label>
                                <textarea name="observacao" id="observacao" cols="30" rows="10" class="form-control"></textarea>
                                <br>
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