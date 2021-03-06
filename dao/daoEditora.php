<?php

require_once "iPage.php";
class daoEditora implements iPage {
    public function remover($source) {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_editora WHERE idtb_editora = :id");
            $statement->bindValue(":id", $source->getIdTbEditora());
            if ($statement->execute()) {
                return "<script> alert('Registo foi excluído com êxito !'); </script>";
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }
    public function salvar($source) {
        try {
            if ($source->getIdTbEditora() != "") {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_editora SET nomeEditora=:nome WHERE idtb_editora = :id;");
                $statement->bindValue(":id", $source->getIdTbEditora());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_editora (nomeEditora) VALUES (:nome)");
            }
            $statement->bindValue(":nome", $source->getNomeEditora());
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
    public function atualizar($source)
    {
        try {
            $statement = Conexao::getInstance()->prepare("SELECT idtb_editora, nomeEditora FROM tb_editora WHERE idtb_editora = :id");
            $statement->bindValue(":id", $source->getIdTbEditora());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdTbEditora($rs->idtb_editora);
                $source->setNomeEditora($rs->nomeEditora);
                return $source;
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }
    public function tabelapaginada(){
        //endereço atual da página
        $endereco = $_SERVER ['PHP_SELF'];
        /* Constantes de configuração */
        define('QTDE_REGISTROS', 10);
        define('RANGE_PAGINAS', 3);
        /* Recebe o número da página via parâmetro na URL */
        $pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
        /* Calcula a linha inicial da consulta */
        $linha_inicial = ($pagina_atual - 1) * QTDE_REGISTROS;
        /* Instrução de consulta para paginação com MySQL */
        $sql = "SELECT idtb_editora, nomeEditora FROM tb_editora LIMIT {$linha_inicial}, " . QTDE_REGISTROS;
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        $dados = $statement->fetchAll(PDO::FETCH_OBJ);
        /* Conta quantos registos existem na tabela */
        $sqlContador = "SELECT COUNT(*) AS total_registros FROM tb_editora";
        $statement = Conexao::getInstance()->prepare($sqlContador);
        $statement->execute();
        $valor = $statement->fetch(PDO::FETCH_OBJ);
        /* Idêntifica a primeira página */
        $primeira_pagina = 1;
        /* Cálcula qual será a última página */
        $ultima_pagina = ceil($valor->total_registros / QTDE_REGISTROS);
        /* Cálcula qual será a página anterior em relação a página atual em exibição */
        $pagina_anterior = ($pagina_atual > 1) ? $pagina_atual - 1 : 0;
        /* Cálcula qual será a pŕoxima página em relação a página atual em exibição */
        $proxima_pagina = ($pagina_atual < $ultima_pagina) ? $pagina_atual + 1 : 0;
        /* Cálcula qual será a página inicial do nosso range */
        $range_inicial = (($pagina_atual - RANGE_PAGINAS) >= 1) ? $pagina_atual - RANGE_PAGINAS : 1;
        /* Cálcula qual será a página final do nosso range */
        $range_final = (($pagina_atual + RANGE_PAGINAS) <= $ultima_pagina) ? $pagina_atual + RANGE_PAGINAS : $ultima_pagina;
        /* Verifica se vai exibir o botão "Primeiro" e "Pŕoximo" */
        $exibir_botao_inicio = ($range_inicial < $pagina_atual) ? 'mostrar' : 'esconder';
        /* Verifica se vai exibir o botão "Anterior" e "Último" */
        $exibir_botao_final = ($range_final > $pagina_atual) ? 'mostrar' : 'esconder';
        if (!empty($dados)) { ?>
            <table class='table table-striped table-bordered'>
            <thead>
                <tr style='text-transform: uppercase;' class='active'>
                    <th style='text-align: center; font-weight: bolder;'>ID</th>
                    <th style='text-align: center; font-weight: bolder;'>Nome</th>
                    <?php if($_SESSION['tipo_usuario'] == 0 || $_SESSION['tipo_usuario'] == 1) { ?>
                        <th style='text-align: center; font-weight: bolder;' colspan='2'>Ações</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $source) { ?>
                    <tr>
                        <td style='text-align: center'><?=$source->idtb_editora?></td>
                        <td style='text-align: center'><?=$source->nomeEditora?></td>
                        <?php if($_SESSION['tipo_usuario'] == 0 || $_SESSION['tipo_usuario'] == 1) { ?>
                            <td style='text-align: center'><a href='?act=upd&id=<?=$source->idtb_editora?>' title='Alterar'><i class='ti-reload'></i></a></td>
                            <td style='text-align: center'><a href='?act=del&id=<?=$source->idtb_editora?>' title='Remover'><i class='ti-close'></i></a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
            </table>
            <div class='box-paginacao' style='text-align: center'>
                <a class='box-navegacao  <?=$exibir_botao_inicio?>' href='<?=$endereco?>?page=<?=$primeira_pagina?>' title='Primeira Página'> Primeira  |</a>
                <a class='box-navegacao  <?=$exibir_botao_inicio?>' href='<?=$endereco?>?page=<?=$pagina_anterior?>' title='Página Anterior'> Anterior  |</a>
                <?php for ($i = $range_inicial; $i <= $range_final; $i++) {
                    $destaque = ($i == $pagina_atual) ? 'destaque' : ''; ?>
                    <a class='box-numero <?=$destaque?>' href='<?=$endereco?>?page=<?=$i?>'> ( <?=$i?> ) </a>
                <?php } ?>
                <a class='box-navegacao <?=$exibir_botao_final?>' href='<?=$endereco?>?page=<?=$proxima_pagina?>' title='Próxima Página'>| Próxima  </a>
                <a class='box-navegacao <?=$exibir_botao_final?>' href='<?=$endereco?>?page=<?=$ultima_pagina?>'  title='Última Página'>| Última  </a>
            </div>
            <?php } else { ?>
                <p class='bg-danger'>Nenhum registro foi encontrado!</p> 
            <?php } 
    }
}
?>