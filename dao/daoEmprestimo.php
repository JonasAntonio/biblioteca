<?php

// require_once "iPage.php";
class daoEmprestimo {
    public function remover($source) {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_emprestimo WHERE tb_usuario_id_tb_usuario = :id");
            $statement->bindValue(":id", $source->getIdUsuario());
            if ($statement->execute()) {
                return "<script> alert('Registo foi excluído com êxito !'); </script>";
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function salvarEmprestimo($source) {
        try {
            foreach ($source->getIdExemplar() as $key => $value) {
                $sql = "
                    DELETE FROM 
                        tb_emprestimo 
                    WHERE 
                        tb_usuario_id_tb_usuario = :id_usuario 
                        AND tb_exemplar_id_tb_exemplar = :id_exemplar
                ";

                $statement = Conexao::getInstance()->prepare($sql);
                $statement->bindValue(":id_usuario", $source->getIdUsuario());
                $statement->bindValue(":id_exemplar", $source->getIdExemplar()[$key]);
                $statement->execute();

                $sql = "
                    INSERT INTO tb_emprestimo (
                        tb_usuario_id_tb_usuario, 
                        tb_exemplar_id_tb_exemplar, 
                        dataEmprestimo, 
                        dataDevolucao, 
                        observacao
                    ) VALUES (
                        :id_usuario, 
                        :id_exemplar, 
                        :dataEmprestimo, 
                        :dataDevolucao, 
                        :observacao
                    )
                ";
                
                $statement = Conexao::getInstance()->prepare($sql);
                $statement->bindValue(":id_usuario", $source->getIdUsuario());
                $statement->bindValue(":id_exemplar", $source->getIdExemplar()[$key]);
                $statement->bindValue(":id_usuario", $source->getIdUsuario());
                $statement->bindValue(":dataEmprestimo", $source->getDataEmprestimo());
                $statement->bindValue(":dataDevolucao", $source->getDataDevolucao());
                $statement->bindValue(":observacao", $source->getObservacao());
                if ($statement->execute()) {
                    if ($statement->rowCount() > 0) {
                        $msg = "<script> alert('Dados cadastrados com sucesso !'); </script>";
                    } else {
                        $msg = "<script> alert('Erro ao tentar efetivar cadastro !'); </script>";
                    }
                } else {
                    throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
                }
            }
            
        } catch (PDOException $erro) {
            $msg = "Erro: " . $erro->getMessage();
        }
        return $msg;
    }

    public function salvar($source) {
        // echo "<pre>";
        // print_r($source);
        // echo "</pre>";
        // try {
        //     if (!empty($source->getIdTbUsuario())) {
        //         // $statement = Conexao::getInstance()->prepare("UPDATE tb_emprestimo SET nomeEditora=:nome WHERE id_tb_usuario = :id;");
        //         // $statement->bindValue(":id", $source->getIdTbUsuario());
        //         // $statement->bindValue(":id_exemplar", $source->getIdTbExemplar());
        //     } else {
        //         $sql = "
        //             INSERT INTO tb_emprestimo (
        //                 tb_usuario_id_tb_usuario, 
        //                 tb_exemplar_id_tb_exemplar, 
        //                 dataEmprestimo, 
        //                 dataDevolucao, 
        //                 observacao
        //             ) VALUES (
        //                 :id_usuario, 
        //                 :id_exemplar, 
        //                 :dataEmprestimo, 
        //                 :dataDevolucao, 
        //                 :observacao
        //             )
        //         ";
        //         $statement = Conexao::getInstance()->prepare($sql);
        //     }
        //     $statement->bindValue(":id_usuario", $source->getIdUsuario());
        //     $statement->bindValue(":id_exemplar", $source->getIdExemplar());
        //     $statement->bindValue(":id_usuario", $source->getIdUsuario());
        //     $statement->bindValue(":dataEmprestimo", $source->getDataEmprestimo());
        //     $statement->bindValue(":dataDevolucao", $source->getDataDevolucao());
        //     $statement->bindValue(":observacao", $source->getObservacao());
        //     if ($statement->execute()) {
        //         if ($statement->rowCount() > 0) {
        //             return "<script> alert('Dados cadastrados com sucesso !'); </script>";
        //         } else {
        //             return "<script> alert('Erro ao tentar efetivar cadastro !'); </script>";
        //         }
        //     } else {
        //         throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
        //     }
        // } catch (PDOException $erro) {
        //     return "Erro: " . $erro->getMessage();
        // }
    }
    public function atualizar($source) {
        try {
            $statement = Conexao::getInstance()->prepare("SELECT id_tb_usuario, nomeEditora FROM tb_emprestimo WHERE id_tb_usuario = :id");
            $statement->bindValue(":id", $source->getIdTbUsuario());
            if ($statement->execute()) {
                $rs = $statement->fetch(PDO::FETCH_OBJ);
                $source->setIdTbEditora($rs->id_tb_usuario);
                $source->setNomeEditora($rs->nomeEditora);
                return $source;
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function tabelapaginada() {
        $endereco = $_SERVER ['PHP_SELF'];
        define('QTDE_REGISTROS', 10);
        define('RANGE_PAGINAS', 3);
        $pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
        $linha_inicial = ($pagina_atual - 1) * QTDE_REGISTROS;

        $sql = "SELECT * FROM tb_emprestimo LIMIT {$linha_inicial}, " . QTDE_REGISTROS;
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        $dados = $statement->fetchAll(PDO::FETCH_OBJ);

        $sqlContador = "SELECT COUNT(*) AS total_registros FROM tb_emprestimo";
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
                    <th style='text-align: center; font-weight: bolder;'>Usuário</th>
                    <th style='text-align: center; font-weight: bolder;'>Exemplar</th>
                    <th style='text-align: center; font-weight: bolder;'>Data de Empréstimo</th>
                    <th style='text-align: center; font-weight: bolder;'>Data de Devolução Prevista</th>
                    <th style='text-align: center; font-weight: bolder;'>Data de Devolução</th>
                    <th style='text-align: center; font-weight: bolder;' colspan='2'>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $source) { ?>
                <tr>
                    <td style='text-align: center'><?=$source->tb_usuario_id_tb_usuario?></td>
                    <td style='text-align: center'><?=$source->tb_exemplar_id_tb_exemplar?></td>
                    <td style='text-align: center'><?=formatarData($source->dataEmprestimo)?></td>
                    <td style='text-align: center'><?=formatarData($source->dataDevolucao)?></td>
                    <td style='text-align: center'><?=formatarData($source->dataDevolucao)?></td>
                    <td style='text-align: center'><a href='?act=upd&id=<?=$source->idtb_exemplar?>' title='Alterar'><i class='ti-reload'></i></a></td>
                    <td style='text-align: center'><a href='?act=del&id=<?=$source->idtb_exemplar?>' title='Remover'><i class='ti-close'></i></a></td>
                </tr>
                <?php } ?>
        </tbody>
            </table>
            <div class='box-paginacao' style='text-align: center'>
                <a class='box-navegacao  $exibir_botao_inicio' href='$endereco?page=$primeira_pagina' title='Primeira Página'> Primeira  |</a>
                <a class='box-navegacao  $exibir_botao_inicio' href='$endereco?page=$pagina_anterior' title='Página Anterior'> Anterior  |</a>
                    <?php for ($i = $range_inicial; $i <= $range_final; $i++) {
                        $destaque = ($i == $pagina_atual) ? 'destaque' : ''; ?>
                        <a class='box-numero $destaque' href='$endereco?page=$i'> ( <?=$i?> ) </a>
                    <?php } ?>
                    <a class='box-navegacao $exibir_botao_final' href='$endereco?page=$proxima_pagina' title='Próxima Página'>| Próxima  </a>
                    <a class='box-navegacao $exibir_botao_final' href='$endereco?page=$ultima_pagina'  title='Última Página'>| Última  </a>
        <?php } else { ?>
            </div><p class='bg-danger'>Nenhum registro foi encontrado!</p>
        <?php } ?>
    <?php }

    public function exemplarExiste($id_exemplar) {
        $sql = "SELECT tb_exemplar_id_tb_exemplar as id_exemplar FROM tb_emprestimo WHERE tb_exemplar_id_tb_exemplar = :id_exemplar";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->bindValue(":id_exemplar", $id_exemplar);
        $statement->execute();
        $dados = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        if ($statement->rowCount() > 0) {
            var_dump($dados);
            return true;
        } else {
            return false;
        }
    }
}
?>