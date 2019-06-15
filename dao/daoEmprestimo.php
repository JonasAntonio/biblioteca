<?php
require_once "dao/daoLivro.php";

class daoEmprestimo {
    public function remover($source) {
        try {
            foreach($this->getExemplaresEmprestados($source->getIdEmprestimo()) AS $exemplar) {
                $sqlExemplar = "UPDATE tb_exemplar SET emprestado = 'N' WHERE idtb_exemplar = :id_exemplar";
                $stmtExemplar = Conexao::getInstance()->prepare($sqlExemplar);
                $stmtExemplar->bindValue(":id_exemplar", $exemplar['id_exemplar']);
                $stmtExemplar->execute();
            }
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_emprestimo WHERE id_emprestimo = :id");
            $statement->bindValue(":id", $source->getIdEmprestimo());
            if ($statement->execute()) {
                return "<script> alert('Registo foi excluído com êxito !'); </script>";
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function salvarEmprestimo($source, $exemplares) {
        $hoje = date('Y-m-d');
        $tipo_usuario = daoUser::getTipoUsuario($source->getIdUsuario());
        if ($tipo_usuario == 2 || $tipo_usuario == 4) {
            $dataVencimento = date('Y-m-d', strtotime('+10 days', strtotime($hoje)));
        } else if($tipo_usuario == 3) {
            $dataVencimento = date('Y-m-d', strtotime('+15 days', strtotime($hoje)));
        } else {
            $dataVencimento = date('Y-m-d');
        }

        try {
            $sql = "
                DELETE FROM 
                    tb_emprestimo_usuario 
                WHERE 
                    id_emprestimo = :id_emprestimo 
            ";
            
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id_emprestimo", $source->getIdEmprestimo());
            $statement->execute();

            $sql = "
                INSERT INTO tb_emprestimo (
                    id_usuario,
                    dataEmprestimo, 
                    dataDevolucao, 
                    dataVencimento, 
                    observacao
                ) VALUES ( 
                    :id_usuario,
                    :dataEmprestimo, 
                    :dataDevolucao,
                    :dataVencimento, 
                    :observacao
                )
            ";
            
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id_usuario", $source->getIdUsuario());
            $statement->bindValue(":dataEmprestimo", $source->getDataEmprestimo());
            $statement->bindValue(":dataDevolucao", $source->getDataDevolucao());
            $statement->bindValue(":dataVencimento", $dataVencimento);
            $statement->bindValue(":observacao", $source->getObservacao());
            if ($statement->execute()) {
                $id_inserido = Conexao::getInstance()->lastInsertId();
                if ($statement->rowCount() > 0) {
                    foreach ($exemplares as $key => $value) {
                        $sql = "
                            INSERT INTO tb_emprestimo_usuario (
                                id_emprestimo,
                                id_exemplar
                            ) VALUES (
                                :id_emprestimo,
                                :id_exemplar
                            )
                        ";
                        
                        $statement = Conexao::getInstance()->prepare($sql);
                        $statement->bindValue(":id_emprestimo", $id_inserido);
                        $statement->bindValue(":id_exemplar", $value);
                        $statement->execute();
                        
                        $sql = "UPDATE tb_exemplar SET emprestado = 'S' WHERE idtb_exemplar = :id_exemplar";
                        $statement = Conexao::getInstance()->prepare($sql);
                        $statement->bindValue(":id_exemplar", $value);
                        $statement->execute();
                    } 
                    return "<script> alert('Dados cadastrados com sucesso !'); </script>";
                } else {
					return "<script> alert('Erro ao tentar efetivar cadastro !'); </script>";
				}
            } else {
				throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
			}
        } catch (PDOException $erro) {
            $msg = "Erro: " . $erro->getMessage();
        }
        return $msg;
    }

    public function atualizar($source) {
        try {
            $sql = "
                UPDATE tb_emprestimo SET 
                    dataDevolucao = :dataDevolucao
                WHERE 
                    id_emprestimo = :id_emprestimo
            ";
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id_emprestimo", $source->getIdEmprestimo());
            $statement->bindValue(":dataDevolucao", date('Y-m-d'));
            if ($statement->execute()) {
                foreach($this->getExemplaresEmprestados($source->getIdEmprestimo()) AS $exemplar) {
                    $sqlExemplar = "UPDATE tb_exemplar SET emprestado = 'N' WHERE idtb_exemplar = :id_exemplar";
                    $stmtExemplar = Conexao::getInstance()->prepare($sqlExemplar);
                    $stmtExemplar->bindValue(":id_exemplar", $exemplar['id_exemplar']);
                    $stmtExemplar->execute();
                }
                return "<script> alert('Devolvido!'); </script>";
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL!'); </script>");
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
                    <th style='text-align: center; font-weight: bolder;'>Exemplares</th>
                    <th style='text-align: center; font-weight: bolder;'>Data de Empréstimo</th>
                    <th style='text-align: center; font-weight: bolder;'>Data de Vencimento</th>
                    <th style='text-align: center; font-weight: bolder;'>Data de Devolução</th>
                    <th style='text-align: center; font-weight: bolder;' colspan='2'>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $source) { ?>
                <tr>
                    <td style='text-align: center'><?=daoUser::getNomeUsuarioId($source->id_usuario)?></td>
                    <td style='text-align: center'>
                        <?php
                            $exemplares = $this->getExemplaresEmprestados($source->id_emprestimo);
                            if(!empty($exemplares)) {
                                foreach ($exemplares as $key => $value) {
                                    $arrExemplares[] = daoLivro::getTituloLivro($value['id_exemplar']);
                                }
                                asort($arrExemplares);
                                echo implode(', ', array_unique($arrExemplares));
                                $arrExemplares = [];  
                            } else {
                                echo "---";
                            }    
                        ?>
                    </td>
                    <td style='text-align: center'><?=formatarData($source->dataEmprestimo)?></td>
                    <td style='text-align: center'><?=formatarData($source->dataVencimento)?></td>
                    <td style='text-align: center'><?=formatarData($source->dataDevolucao)?></td>
                    <td style='text-align: center'><a href='?act=upd&id=<?=$source->id_emprestimo?>' title='Alterar'><i class='fa fa-archive'></i></a></td>
                    <td style='text-align: center'><a href='?act=del&id=<?=$source->id_emprestimo?>' title='Remover'><i class='ti-close'></i></a></td>
                </tr>
                <?php } ?>
        </tbody>
            </table>
            <div class='box-paginacao' style='text-align: center'>
                <a class='box-navegacao  $exibir_botao_inicio' href='<?=$endereco?>?page=<?=$primeira_pagina?>' title='Primeira Página'> Primeira  |</a>
                <a class='box-navegacao  $exibir_botao_inicio' href='<?=$endereco?>?page=<?=$pagina_anterior?>' title='Página Anterior'> Anterior  |</a>
                <?php for ($i = $range_inicial; $i <= $range_final; $i++) {
                    $destaque = ($i == $pagina_atual) ? 'destaque' : ''; ?>
                    <a class='box-numero $destaque' href='<?=$endereco?>?page=<?=$i?>'> ( <?=$i?> ) </a>
                <?php } ?>
                <a class='box-navegacao <?=$exibir_botao_final?>' href='<?=$endereco?>?page=<?=$proxima_pagina?>' title='Próxima Página'>| Próxima  </a>
                <a class='box-navegacao <?=$exibir_botao_final?>' href='<?=$endereco?>?page=<?=$ultima_pagina?>'  title='Última Página'>| Última  </a>
        <?php } else { ?>
            </div><p class='bg-danger'>Nenhum registro foi encontrado!</p>
        <?php } ?>
    <?php }

    public function exemplarDisponivel($id_exemplar) {
        $sql = "SELECT id_exemplar FROM tb_emprestimo_usuario WHERE id_exemplar = :id_exemplar";
        // $sql = "
        //     SELECT 
        //         eu.id_exemplar
        //     FROM
        //         tb_emprestimo_usuario as eu
        //             right JOIN
        //         tb_emprestimo as e ON eu.id_emprestimo = e.id_emprestimo
        //     WHERE
        //         (eu.id_exemplar = :id_exemplar
        //         AND e.dataDevolucao != '0000-00-00')
                
        // ";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->bindValue(":id_exemplar", $id_exemplar);
        $statement->execute();
        $dados = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getExemplaresEmprestados($id_emprestimo) {
            $sql = "SELECT * FROM tb_emprestimo_usuario WHERE id_emprestimo = :id_emprestimo";
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id_emprestimo", $id_emprestimo);
            $statement->execute();
            $exemplares = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $exemplares;
    }

}


?>