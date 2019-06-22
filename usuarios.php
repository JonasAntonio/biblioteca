<?php

require_once "view/template.php";
require_once "dao/daoUser.php";
require_once "modelo/Usuario.php";
require_once "db/Conexao.php";

$dao = new daoUser();

template::header();
$_SESSION['active_window'] = "usuarios";
template::sidebar();
template::mainpanel();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : "";
    $email = (isset($_POST["email"]) && $_POST["email"] != null) ? $_POST["email"] : "";
    $senha = (isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : "";
    $senha = Usuario::encodePassword($senha);
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = null;
    $tipo = null;
    $email = null;
    $senha = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "" ) {
    $usuario = new Usuario($id, $nome, $tipo, $senha, $email);
    $msg = $dao->salvar($usuario);
    $id = null;
    $nome = null;
    $tipo = null;
    $email = null;
    $senha = null;
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    $usuario = new Usuario($id, '', '', '', '');
    $resultado = $dao->atualizar($usuario);
    $id = $resultado->getIdTbUsuario();
    $nome = $resultado->getNomeUsuario();
    $tipo = $resultado->getTipo();
    $email = $resultado->getEmail();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    $usuario = new Usuario($id, "", "", "", "");
    $msg = $dao->remover($usuario);
    $id = null;
}

?>

    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='card'>
                        <div class='header'>
                            <h4 class='title'>Usuários</h4>
                            <p class='category'>Lista de Usuários do Sistema</p>

                        </div>
                        <div class='content table-responsive'>
                            <form action="?act=save&id=" method="POST" name="form1">
                            <?php if($_SESSION['tipo_usuario'] == 0) { ?>
                                <input type="hidden" name="id" value="<?php
                                // Preenche o id no campo id com um valor "value"
                                echo (!empty($id)) ? $id : '';
                                ?>"/>
                                <Label>Nome</Label>
                                <input class="form-control" type="text" size="50" name="nome" value="<?php
                                // Preenche o nome no campo nome com um valor "value"
                                echo (!empty($nome)) ? $nome : '';
                                ?>" required/>
                                <Label>Tipo</Label>
                                <select class="form-control multiselect" name="tipo">
                                    <option value="">--Selecione--</option>
                                    <?
                                    foreach (Usuario::tipoUsuario() as $value) {?>
                                        <option value="<?=$value['valor']?>"
                                            <?php if(!empty($tipo)) echo $tipo == $value['valor'] ? 'selected' : ''?>>
                                            <?=$value['nome']?>
                                        </option>
                                    <?}?>
                                </select>
                                <Label>Email</Label>
                                <input class="form-control" type="text" size="50" name="email" value="<?php
                                // Preenche o nome no campo email com um valor "value"
                                echo (!empty($email)) ? $email : '';
                                ?>" required/>
                                <Label>Senha</Label>
                                <input class="form-control" type="password" size="50" name="senha"/>
                                <Label>Confirmar Senha</Label>
                                <input class="form-control" type="password" size="50" name="confirmar_senha"/>
                                <br/>
                                <input class="btn btn-success" type="submit" value="REGISTRAR">
                                <input class="btn btn-success" type="button" onclick='document.location="pdf/tcpdf/relatorio.php?mode=usuarios"' value="EXPORTAR">
                                <hr>
                            </form>
                            <?php 
                                echo (isset($msg) && ($msg != null || $msg != "")) ? $msg : '';
                            }
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