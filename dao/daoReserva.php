<?php
require_once "dao/daoLivro.php";

class daoReserva {
    public function remover($source) {
        try {
            foreach($this->getExemplaresReservados($source->getIdReserva()) AS $exemplar) {
                $sqlExemplar = "UPDATE tb_exemplar SET reservado = 'N' WHERE idtb_exemplar = :id_exemplar";
                $stmtExemplar = Conexao::getInstance()->prepare($sqlExemplar);
                $stmtExemplar->bindValue(":id_exemplar", $exemplar['id_exemplar']);
                $stmtExemplar->execute();
            }
            $sql = "UPDATE tb_reserva SET status = 'C' WHERE id_reserva = :id";
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id", $source->getIdReserva());
            if ($statement->execute()) {
                return "<script> alert('Registo foi cancelado com êxito !'); </script>";
            } else {
                throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
            }
        } catch (PDOException $erro) {
            return "Erro: " . $erro->getMessage();
        }
    }

    public function salvarReserva($source, $exemplares) {
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
                    tb_reserva_usuario 
                WHERE 
                    id_reserva = :id_reserva 
            ";
            
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id_reserva", $source->getIdReserva());
            $statement->execute();

            $sql = "
                INSERT INTO tb_reserva (
                    id_usuario,
                    dataReserva, 
                    dataVencimento, 
                    observacao
                ) VALUES ( 
                    :id_usuario,
                    :dataReserva, 
                    :dataVencimento, 
                    :observacao
                )
            ";
            
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id_usuario", $source->getIdUsuario());
            $statement->bindValue(":dataReserva", $source->getDataReserva());
            $statement->bindValue(":dataVencimento", $dataVencimento);
            $statement->bindValue(":observacao", $source->getObservacao());
            if ($statement->execute()) {
                $id_inserido = Conexao::getInstance()->lastInsertId();
                if ($statement->rowCount() > 0) {
                    foreach ($exemplares as $value) {
                        $sql = "
                            INSERT INTO tb_reserva_usuario (
                                id_reserva,
                                id_exemplar
                            ) VALUES (
                                :id_reserva,
                                :id_exemplar
                            )
                        ";
                        
                        $statement = Conexao::getInstance()->prepare($sql);
                        $statement->bindValue(":id_reserva", $id_inserido);
                        $statement->bindValue(":id_exemplar", $value);
                        $statement->execute();
                        
                        $sql = "UPDATE tb_exemplar SET reservado = 'S' WHERE idtb_exemplar = :id_exemplar";
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
                UPDATE tb_reserva SET 
                    status = 'E'
                WHERE 
                    id_reserva = :id_reserva
            ";
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id_reserva", $source->getIdReserva());
            if ($statement->execute()) {
                $hoje = date('Y-m-d');
                $tipo_usuario = daoUser::getTipoUsuario($source->getIdUsuario());
                if ($tipo_usuario == 2 || $tipo_usuario == 4) {
                    $dataVencimento = date('Y-m-d', strtotime('+10 days', strtotime($hoje)));
                } else if($tipo_usuario == 3) {
                    $dataVencimento = date('Y-m-d', strtotime('+15 days', strtotime($hoje)));
                } else {
                    $dataVencimento = date('Y-m-d');
                }

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
                $statement->bindValue(":dataEmprestimo", $hoje);
                $statement->bindValue(":dataDevolucao", '0000-00-00');
                $statement->bindValue(":dataVencimento", $dataVencimento);
                $statement->bindValue(":observacao", $source->getObservacao());
                $statement->execute();
                $id_inserido_emprestimo = Conexao::getInstance()->lastInsertId();
                foreach($this->getExemplaresReservados($source->getIdReserva()) AS $exemplar) {
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
                    $statement->bindValue(":id_emprestimo", $id_inserido_emprestimo);
                    $statement->bindValue(":id_exemplar", $exemplar['id_exemplar']);
                    $statement->execute();
                    $sqlExemplar = "
                        UPDATE tb_exemplar SET 
                            emprestado = 'S', 
                            reservado = 'N' 
                        WHERE 
                            idtb_exemplar = :id_exemplar
                    ";
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

        $sql = "SELECT * FROM tb_reserva LIMIT {$linha_inicial}, " . QTDE_REGISTROS;
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->execute();
        $dados = $statement->fetchAll(PDO::FETCH_OBJ);

        $sqlContador = "SELECT COUNT(*) AS total_registros FROM tb_reserva";
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
                    <th style='text-align: center; font-weight: bolder;'>Data de Reserva</th>
                    <th style='text-align: center; font-weight: bolder;'>Data de Vencimento</th>
                    <th style='text-align: center; font-weight: bolder;'>Status</th>
                    <th style='text-align: center; font-weight: bolder;' colspan='2'>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $source) { ?>
                <tr>
                    <td style='text-align: center'><?=daoUser::getNomeUsuarioId($source->id_usuario)?></td>
                    <td style='text-align: center'>
                        <?php
                            $exemplares = $this->getExemplaresReservados($source->id_reserva);
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
                    <td style='text-align: center'><?=formatarData($source->dataReserva)?></td>
                    <td style='text-align: center'><?=formatarData($source->dataVencimento)?></td>
                    <td style='text-align: center'>
                        <?
                        switch ($source->status) {
                            case 'R':
                                echo 'Reservado';
                                break;
                            case 'C':
                                echo 'Cancelado';
                                break;
                            case 'E':
                                echo 'Emprestado';
                                break;
                            default:
                                echo '---';
                                break;
                        }
                        ?>
                    </td>
                    <?php if($source->status != 'E') { ?>
                        <td style='text-align: center'><a href='?act=upd&id=<?=$source->id_reserva?>&id_usuario=<?=$source->id_usuario?>' title='Emprestar'><i class='fa fa-angle-double-up'></i></a></td>
                        <td style='text-align: center'><a href='?act=del&id=<?=$source->id_reserva?>' title='Remover'><i class='ti-close'></i></a></td>
                    <?php } else {?>
                        <td></td>
                        <td></td>
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
        <?php } else { ?>
            </div><p class='bg-danger'>Nenhum registro foi encontrado!</p>
        <?php } ?>
    <?php }

    public static function getExemplaresReservados($id_reserva) {
            $sql = "SELECT * FROM tb_reserva_usuario WHERE id_reserva = :id_reserva";
            $statement = Conexao::getInstance()->prepare($sql);
            $statement->bindValue(":id_reserva", $id_reserva);
            $statement->execute();
            $exemplares = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $exemplares;
    }

}


?>