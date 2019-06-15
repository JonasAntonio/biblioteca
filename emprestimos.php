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

$hoje = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_emprestimo = (isset($_POST["id_emprestimo"]) && $_POST["id_emprestimo"] != null) ? $_POST["id_emprestimo"] : "";
    $id_usuario = (isset($_POST["id_usuario"]) && $_POST["id_usuario"] != null) ? $_POST["id_usuario"] : "";
    $exemplares = (isset($_POST["exemplares"]) && $_POST["exemplares"] != null) ? $_POST["exemplares"] : "";
    $dataEmprestimo = $hoje;
    $dataDevolucao = (isset($_POST["dataDevolucao"]) && $_POST["dataDevolucao"] != null) ? $_POST["dataDevolucao"] : "";
    $observacao = (isset($_POST["observacao"]) && $_POST["observacao"] != null) ? $_POST["observacao"] : "";
    
} else if (!isset($id_emprestimo)) {
    $id_emprestimo = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $id_usuario = NULL;
    $exemplares = NULL;
    $dataEmprestimo = NULL;
    $dataDevolucao = NULL;
    $observacao = NULL;
    
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" ) {
    $emprestimo = new Emprestimo($id_emprestimo, $id_usuario, $dataEmprestimo, $dataDevolucao, '', $observacao);
    $msg = $dao->salvarEmprestimo($emprestimo, $exemplares);
    $id_usuario = NULL;
    $id_emprestimo = NULL;
    $exemplares = NULL;
    $dataEmprestimo = NULL;
    $dataDevolucao = NULL;
    $observacao = NULL;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id_emprestimo  != "") {
    $emprestimo = new Emprestimo($id_emprestimo, '', '', '', '', '');
    $resultado = $dao->atualizar($emprestimo);
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id_emprestimo  != "") {
    $emprestimo = new Emprestimo($id_emprestimo , "", "", "", "", "");
    $msg = $dao->remover($emprestimo);
    $id_emprestimo  = null;
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
                                <input type="hidden" name="id_usuario" value="<?=(!empty($id_usuario)) ? $id_usuario : ''?>"/>
                                <Label for="usuario">Usuário</Label>
                                <select class="form-control multiselect" name="id_usuario">
                                    <option value="">--Selecione--</option>
                                    <? foreach ($daoUser->listAll() as $value) {?>
                                        <option value="<?=$value['idtb_usuario']?>"
                                            <?php if(!empty($id_usuario)) echo $id_usuario == $value['idtb_usuario'] ? 'selected' : ''?>>
                                            <?=$value['nomeUsuario']?>
                                        </option>
                                    <?}?>
                                </select>
                                <Label>Exemplares</Label>
                                <select class="form-control multiselect" name="exemplares[]" multiple>
                                    <? foreach ($daoExemplar->listAll() as $value) {
                                        // if($dao->exemplarDisponivel($value['idtb_exemplar'])) {
                                            if(true) {
                                            ?>
                                        <option value="<?=$value['idtb_exemplar']?>"
                                            <?php if(!empty($tipo)) echo $tipo == $value['idtb_exemplar'] ? 'selected' : ''?>>
                                            <?=$value['titulo']?>
                                        </option>
                                        <? }
                                    } ?>
                                </select>
                                <label for="observacao">Observação</label>
                                <textarea name="observacao" id="observacao" cols="30" rows="10" class="form-control"></textarea>
                                <br>
                                <input class="btn btn-success" type="submit" value="REGISTRAR">
                                <!-- <input class="btn btn-success" type="button" onclick='document.location="pdf/tcpdf/relatorio.php"' value="EXPORTAR"> -->
                                <hr>
                            </form>
                            <?php
                            echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
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