<?php

require_once "iPage.php";
require_once "modelo/Livro.php";

class daoLivro implements iPage{

	public function remover($source){
		try {
			$statement = Conexao::getInstance()->prepare("
				DELETE FROM tb_livro_has_tb_autores WHERE tb_livro_id_tb_livro = :id;
				DELETE FROM tb_livro WHERE idtb_livro = :id;
			");
			$statement->bindValue(":id", $source->getIdTbLivro());
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

	}

	public function salvarLivro($source, $autores){
		try {
			if (!empty($source->getIdTbLivro())) {
				$statement = Conexao::getInstance()->prepare("
                    UPDATE tb_livro SET 
                        titulo=:titulo,
                        isbn=:isbn,
                        edicao=:edicao,
                        ano=:ano,
                        upload=:upload,
                        tb_editora_id_tb_editora=:tb_editora_id_tb_editora,
                        tb_categoria_id_tb_categoria=:tb_categoria_id_tb_categoria
                    WHERE 
												idtb_livro = :id;
				");
				$statement->bindValue(":id", $source->getIdTbLivro());
			} else {
				$statement = Conexao::getInstance()->prepare("
					INSERT INTO tb_livro (
						titulo, isbn, edicao, ano, upload, tb_editora_id_tb_editora, tb_categoria_id_tb_categoria
					) VALUES (
						:titulo, :isbn, :edicao, :ano, :upload, :tb_editora_id_tb_editora, :tb_categoria_id_tb_categoria
					)
				");
			}

			$statement->bindValue(":titulo", $source->getTitulo());
			$statement->bindValue(":isbn", $source->getISBN());
			$statement->bindValue(":edicao", $source->getEdicao());
			$statement->bindValue(":ano", $source->getAno());
			$statement->bindValue(":upload", $source->getUpload());
			$statement->bindValue(":tb_editora_id_tb_editora", $source->getIdTbEditora());
			$statement->bindValue(":tb_categoria_id_tb_categoria", $source->getIdTbCategoria());
			
			if ($statement->execute()) {
				$id_livro = Conexao::getInstance()->lastInsertId();
				if ($statement->rowCount() > 0) {
					$stmtDel = Conexao::getInstance()->prepare("
						DELETE FROM tb_livro_has_tb_autores WHERE tb_livro_id_tb_livro = :id_livro;
					");
					foreach ($autores as $key => $value) {
						$stmt = Conexao::getInstance()->prepare("
							INSERT INTO 
								tb_livro_has_tb_autores (
									tb_livro_id_tb_livro, 
									tb_autores_id_tb_autores
								) VALUES (
									:id_livro, 
									:id_autor
							)"
						);
						$stmt->bindValue(":id_livro", $id_livro);
						$stmt->bindValue(":id_autor", $value);
						$stmtDel->bindValue(":id_livro", $id_livro);
						$stmtDel->execute();
						$stmt->execute();
					}
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

	public function getAllEditoras() {
		$sql = "SELECT * FROM tb_editora";
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->execute();
		$dados = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $dados;
	}

	public function getAllCategorias() {
		$sql = "SELECT * FROM tb_categoria";
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->execute();
		$dados = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $dados;
	}

	public function getAllAutores() {
		$sql = "SELECT * FROM tb_autores";
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->execute();
		$dados = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $dados;
	}

	public static function getCategoria($id) {
		$sql = "SELECT * FROM tb_categoria WHERE idtb_categoria = $id";
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->execute();
		$dados = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $dados;
	}

	public static function getEditora($id) {
		$sql = "SELECT * FROM tb_editora WHERE idtb_editora = $id";
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->execute();
		$dados = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $dados;
	}

	public static function getAutor($id) {
		$sql = "SELECT * FROM tb_autores WHERE idtb_autores = $id";
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->execute();
		$dados = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $dados;
	}

	public function atualizar($source)
	{
		try {
			$sql = "SELECT * FROM tb_livro WHERE idtb_livro = :id";
			$statement = Conexao::getInstance()->prepare($sql);
			$statement->bindValue(":id", $source->getIdtbLivro());
			if ($statement->execute()) {
					$rs = $statement->fetch(PDO::FETCH_OBJ);
					$source->setIdTbLivro($rs->idtb_livro);
					$source->settitulo($rs->titulo);
					$source->setISBN($rs->isbn);
					$source->setEdicao($rs->edicao);
					$source->setAno($rs->ano);
					$source->setIdTbEditora($rs->tb_editora_id_tb_editora);
					$source->setIdTbCategoria($rs->tb_categoria_id_tb_categoria);
					$source->setUpload($rs->upload);
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
		$sql = "SELECT idtb_livro, titulo, isbn, edicao, ano, tb_editora_id_tb_editora, tb_categoria_id_tb_categoria FROM tb_livro LIMIT {$linha_inicial}, " . QTDE_REGISTROS;
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->execute();
		$dados = $statement->fetchAll(PDO::FETCH_OBJ);
		$sqlContador = "SELECT COUNT(*) AS total_registros FROM tb_livro";
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
                        <th style='text-align: center; font-weight: bolder;'>ISBN</th>
                        <th style='text-align: center; font-weight: bolder;'>Edição</th>
                        <th style='text-align: center; font-weight: bolder;'>Ano</th>
                        <th style='text-align: center; font-weight: bolder;'>Editora</th>
                        <th style='text-align: center; font-weight: bolder;'>Categoria</th>
                        <?php if($_SESSION['tipo_usuario'] == 0 || $_SESSION['tipo_usuario'] == 1) { ?>
                            <th style='text-align: center; font-weight: bolder;' colspan='2'>Ações</th>
                        <?php }?>
                    </tr>
                </thead>
            <tbody>
			<?php foreach ($dados as $source) { 
				$editora = daoLivro::getEditora($source->tb_editora_id_tb_editora);
                $categoria = daoLivro::getCategoria($source->tb_categoria_id_tb_categoria);
                ?>
				<tr>
                    <td style='text-align: center'><?=$source->idtb_livro?></td>
                    <td style='text-align: center'><?=$source->titulo?></td>
                    <td style='text-align: center'><?=$source->isbn?></td>
                    <td style='text-align: center'><?=$source->edicao?></td>
                    <td style='text-align: center'><?=$source->ano?></td>
                    <td style='text-align: center'><?=$editora[0]['nomeEditora']?></td>
                    <td style='text-align: center'><?=$categoria[0]['nomeCategoria']?></td>
                    <?php if($_SESSION['tipo_usuario'] == 0 || $_SESSION['tipo_usuario'] == 1) { ?>
                    <td style='text-align: center'><a href='?act=upd&id=<?=$source->idtb_livro?>' title='Alterar'><i class='ti-reload'></i></a></td>
                    <td style='text-align: center'><a href='?act=del&id=<?=$source->idtb_livro?>' title='Remover'><i class='ti-close'></i></a></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
            </table>
            <div class='box-paginacao' style='text-align: center'>
                <a class='box-navegacao  <?=$exibir_botao_inicio?>' href='<?=$endereco?>?page=<?=$primeira_pagina?>' title='Primeira Página'> Primeira  |</a>
                <a class='box-navegacao  <?=$exibir_botao_inicio?>' href='<?=$endereco?>?page=<?=$pagina_anterior?>' title='Página Anterior'> Anterior  |</a>
                <?php for ($i = $range_inicial; $i <= $range_final; $i++) {
                    $destaque = ($i == $pagina_atual) ? 'destaque' : ''; 
                    ?>
                    <a class='box-numero <?=$destaque?>' href='<?=$endereco?>?page=<?=$i?>'> ( <?=$i?> ) </a>
                <?php } ?>
                <a class='box-navegacao <?=$exibir_botao_final?>' href='<?=$endereco?>?page=<?=$proxima_pagina?>' title='Próxima Página'>| Próxima  </a>
                <a class='box-navegacao <?=$exibir_botao_final?>' href='<?=$endereco?>?page=<?=$ultima_pagina?>'  title='Última Página'>| Última  </a>
            </div>
		
        <?php } else { ?>
            <p class='bg-danger'>Nenhum registro foi encontrado!</p>    
        <?php } 
	}

	public function listAll() {
		$sql = "SELECT * FROM tb_livro";
		$statement = Conexao::getInstance()->prepare($sql);
		if ($statement->execute()) {
			$rs = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $rs;
		} else {
			throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
		}
	}

	public function getAutoresLivro($id_livro) {
		$sql = "SELECT tb_autores_id_tb_autores AS id_autores FROM tb_livro_has_tb_autores WHERE tb_livro_id_tb_livro = :id_livro";
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->bindValue(":id_livro", $id_livro);
		if ($statement->execute()) {
			$rs = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $rs;
		} else {
			throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
		}
	}

	public static function getTituloLivro($id) {
		$sql = "
					SELECT 
					l.titulo
			FROM
					tb_livro AS l
						JOIN
					tb_exemplar e ON e.tb_livro_id_tb_livro = l.idtb_livro
			WHERE
				e.idtb_exemplar = :id
			GROUP BY e.tb_livro_id_tb_livro
		";
		$statement = Conexao::getInstance()->prepare($sql);
		$statement->bindValue(":id", $id);
		if ($statement->execute()) {
			$rs = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $rs[0]['titulo'];
		} else {
			throw new PDOException("<script> alert('Não foi possível executar a declaração SQL !'); </script>");
		}
	}

}

?>
