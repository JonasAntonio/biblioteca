<?php

require_once "iPage.php";
class daoAutor implements iPage {
    public function remover($source) {
        try {
            $statement = Conexao::getInstance()->prepare("DELETE FROM tb_emprestimo WHERE id_tb_usuario = :id AND id_tb_exemplar = :id_exemplar");
            $statement->bindValue(":id", $source->getIdTbUsuario());
            $statement->bindValue(":id_exemplar", $source->getIdTbExemplar());
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
            if ($source->getIdTbUsuario() != "") {
                $statement = Conexao::getInstance()->prepare("UPDATE tb_emprestimo SET nomeEditora=:nome WHERE id_tb_usuario = :id;");
                $statement->bindValue(":id", $source->getIdTbUsuario());
                $statement->bindValue(":id_exemplar", $source->getIdTbExemplar());
            } else {
                $statement = Conexao::getInstance()->prepare("INSERT INTO tb_emprestimo (nomeEditora) VALUES (:nome)");
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
    public function tabelapaginada(){

    }
}
?>