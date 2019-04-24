<?php

require_once "iPage.php";

class daoLivro implements iPage{
//    public function salvarLivro ($ano, $edicao,$tb_editora_idtb_editora, $tb_categoria_idtb_categoria,$titulo, $upload){
//    	echo " chegou na função";
//	$cmd = Conexao::getInstance()->prepare("INSERT INTO tb_livro (ano, edicao, tb_editora_idtb_editora, tb_categoria_idtb_categoria,titulo, upload) VALUES (:ano, :edicao, :tb_editora_idtb_editora, :tb_categoria_idtb_categoria, :titulo, :upload) ");
//
//	$cmd->bindValue(":ano", $ano);
//	$cmd->bindValue(":edicao", $edicao);
//	$cmd->bindValue(":tb_editora_idtb_editora", $tb_editora_idtb_editora);
//	$cmd->bindValue(":tb_categoria_idtb_categoria", $tb_categoria_idtb_categoria);
//	$cmd->bindValue(":titulo", $titulo);
//	$cmd->bindValue(":upload", $upload);
//	$cmd->execute();
//	}

	public function remover($source){
		try {
			$statement = Conexao::getInstance()->prepare("DELETE FROM tb_livro WHERE idtb_livro = :id");
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
		try {
			if (!empty($source->getIdTbLivro())) {
				$statement = Conexao::getInstance()->prepare("
                    UPDATE tb_livro SET 
                        titulo=:titulo,
                        isbn=:isbn,
                        edicao=:edicao,
                        ano=:ano
                        upload=:upload
                        tb_editora_idtb_editora=:tb_editora_idtb_editora
                        tb_categoria_idtb_categoria=:tb_categoria_idtb_categoria
                    WHERE 
                        idtb_livro = :id;");
				$statement->bindValue(":id", $source->getIdTbLivro());
			} else {
				$statement = Conexao::getInstance()->prepare("
					INSERT INTO tb_usuario (
						titulo, isbn, edicao, ano, upload, tb_editora_idtb_editora, tb_categoria_idtb_categoria
					) VALUES (
						:titulo, :isbn, :edicao, :ano, :upload, :tb_editora_idtb_editora, :tb_categoria_idtb_categoria
					)
				");
			}

			$statement->bindValue(":titulo", $source->getTitulo());
			$statement->bindValue(":isbn", $source->getISBN());
			$statement->bindValue(":edicao", $source->getEdicao());
			$statement->bindValue(":ano", $source->getAno());
			$statement->bindValue(":upload", $source->getUpload());
			$statement->bindValue(":tb_editora_idtb_editora", $source->getIdTbEditora());
			$statement->bindValue(":tb_categoria_idtb_categoria", $source->getIdTbCategoria());
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

	public function atualizar($source)
	{
		// TODO: Implement atualizar() method.
	}

	public function tabelapaginada()
	{
		// TODO: Implement tabelapaginada() method.
	}
}




 ?>
