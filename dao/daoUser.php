<?php
require_once "iPage.php";

class daoUser implements iPage {

    public function auth($email, $senha) {
        $cmd = Conexao::getInstance()->prepare("SELECT senha FROM tb_usuario WHERE email = :email");
        $cmd->bindValue(":email", $email);
        $cmd->execute();
        $rs = $cmd->fetch(PDO::FETCH_ASSOC);
        if($rs == NULL){
            return false;
        }else{
            if(password_verify($senha, $rs['senha'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function remover($source){
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_usuario WHERE idtb_usuario = :id");
            $statement->bindValue(":id", $source->getIdTbUsuario());
            if ($statement->execute()) {
                return "<script> alert('Registo foi excluído com êxito !'); </script>";
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function salvar($source){
        try {
            if (!empty($source->getIdTbUsuario())) {
                $statement = Conexao::getInstance()->prepare("
                    UPDATE tb_usuario SET 
                        nomeUsuario=:nomeUsuario,
                        tipo=:tipo,
                        email=:email,
                        senha=:senha
                    WHERE 
                        idtb_usuario = :id;");
                $statement->bindValue(":id", $source->getIdTbUsuario());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_usuario (nomeUsuario, tipo, email, senha) VALUES (:nomeUsuario, :tipo, :email, :senha)");
            }

            $statement->bindValue(":nomeUsuario", $source->getNomeUsuario());
            $statement->bindValue(":tipo", $source->getTipo());
            $statement->bindValue(":senha", $source->getSenha());
            $statement->bindValue(":email", $source->getEmail());
            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    return "<script> alert('Dados cadastrados com sucesso !'); </script>";
                } else {
                    return "<script> alert('Erro ao tentar efetivar cadastro !'); </script>";
                }
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function atualizar($source){
        try {
            $statement = Conexao::getInstance()->prepare("SELECT * FROM tb_usuario WHERE idtb_usuario = :id");
            $statement->bindValue(":id", $source->getIdTbUsuario());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdTbUsuario($rs->idtb_usuario);
                $source->setNomeUsuario($rs->nomeUsuario);
                $source->setTipo($rs->tipo);
                $source->setSenha(Usuario::encodePassword($rs->senha));
                $source->setEmail($rs->email);
                return $source;
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function tabelapaginada(){
        $endereco = $_SERVER ['PHP_SELF'];
        define('QTDE_REGISTROS', 10);
        define('RANGE_PAGINAS', 3);
        $pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
        $linha_inicial = ($pagina_atual - 1) * QTDE_REGISTROS;
        $sql = "SELECT idtb_usuario, nomeUsuario, tipo, email FROM tb_usuario LIMIT {$linha_inicial}, " . QTDE_REGISTROS;
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        $dados = $statement->fetchAll(PDO::FETCH_OBJ);
        $sqlContador = "SELECT COUNT(*) AS total_registros FROM tb_usuario";
        $statement = Conexao::getInstance()->prepare($sqlContador);
        $statement->execute();
        $valor = $statement->fetch(PDO::FETCH_OBJ);
        $primeira_pagina = 1;
        $ultima_pagina = ceil($valor->total_registros / QTDE_REGISTROS);
        $pagina_anterior = ($pagina_atual > 1) ? $pagina_atual - 1 : 0;
        $proxima_pagina = ($pagina_atual < $ultima_pagina) ? $pagina_atual + 1 : 0;
        $range_inicial = (($pagina_atual - RANGE_PAGINAS) >= 1) ? $pagina_atual - RANGE_PAGINAS : 1;
        $range_final = (($pagina_atual + RANGE_PAGINAS) <= $ultima_pagina) ? $pagina_atual + RANGE_PAGINAS : $ultima_pagina;
        $exibir_botao_inicio = ($range_inicial < $pagina_atual) ? 'mostrar' : 'esconder';
        $exibir_botao_final = ($range_final > $pagina_atual) ? 'mostrar' : 'esconder';
        if (!empty($dados)) { ?>
            
            <table class='table table-striped table-bordered'>
                <thead>
                    <tr style='text-transform: uppercase;' class='active'>
                        <th style='text-align: center; font-weight: bolder;'>ID</th>
                        <th style='text-align: center; font-weight: bolder;'>Nome</th>
                        <th style='text-align: center; font-weight: bolder;'>Tipo</th>
                        <th style='text-align: center; font-weight: bolder;'>Email</th>
                        <?php if($_SESSION['tipo_usuario'] == 0) { ?>
                            <th style='text-align: center; font-weight: bolder;' colspan='2'>Ações</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados as $source) { ?>
                        <tr>
                            <td style='text-align: center'><?=$source->idtb_usuario?></td>
                            <td style='text-align: center'><?=$source->nomeUsuario?></td>
                            <td style='text-align: center'><?=Usuario::getTipoText($source->tipo)?></td>
                            <td style='text-align: center'><?=$source->email?></td>
                            <?php if($_SESSION['tipo_usuario'] == 0) { ?>
                                <td style='text-align: center'><a href='?act=upd&id=<?=$source->idtb_usuario?>' title='Alterar'><i class='ti-reload'></i></a></td>
                                <td style='text-align: center'><a href='?act=del&id=<?=$source->idtb_usuario?>' title='Remover'><i class='ti-close'></i></a></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class='box-paginacao' style='text-align: center'>
                <a class='box-navegacao  $exibir_botao_inicio' href='<?=$endereco?>?page=<?=$primeira_pagina?>' title='Primeira Página'> Primeira  |</a>
                <a class='box-navegacao  $exibir_botao_inicio' href='<?=$endereco?>?page=<?=$pagina_anterior?>' title='Página Anterior'> Anterior  |</a>
                    
                    <?php for ($i = $range_inicial; $i <= $range_final; $i++) { 
                        $destaque = ($i == $pagina_atual) ? 'destaque' : '';
                        ?>
                        <a class='box-numero <?=$destaque?>' href='<?=$endereco?>?page=<?=$i?>'> ( <?=$i?> ) </a>
                    <?php } ?>
                    <a class='box-navegacao $exibir_botao_final' href='<?=$endereco?>?page=<?=$proxima_pagina?>' title='Próxima Página'>| Próxima  </a>
                    <a class='box-navegacao $exibir_botao_final' href='<?=$endereco?>?page=<?=$ultima_pagina?>' title='Última Página'>| Última  </a>
            </div>
        <?php } else { ?>
            <p class='bg-danger'>Nenhum registro foi encontrado!</p>
        <?php }
    }

    public function listAll(){
        $sql = "SELECT * FROM tb_usuario";
		$statement = Conexao::getInstance()->prepare($sql);
		if ($statement->execute()) {
			$rs = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $rs;
		} else {
			throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
		}
    }

    public static function getNomeUsuarioId($id) {
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

}