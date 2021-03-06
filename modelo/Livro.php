<?php

class Livro {
    private $idtb_livro;
    private $titulo;
    private $isbn;
    private $edicao;
    private $ano;
    private $upload;
    private $tb_editora_id_tb_editora;
    private $tb_categoria_id_tb_categoria;

    public function __construct($idtb_livro, $titulo, $isbn, $edicao, $ano, $upload, $tb_editora_id_tb_editora, $id_tb_categoria) {
        $this->idtb_livro = $idtb_livro;
        $this->titulo = $titulo;
        $this->isbn = $isbn;
        $this->edicao = $edicao;
        $this->ano = $ano;
        $this->upload = $upload;
        $this->ano = $ano;
        $this->tb_editora_id_tb_editora = $tb_editora_id_tb_editora;
        $this->tb_categoria_id_tb_categoria = $id_tb_categoria;
    }

    public function getIdTbLivro() {
        return $this->idtb_livro;
    }

    public function setIdTbLivro($idtb_livro) {
        $this->idtb_livro = $idtb_livro;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getISBN() {
        return $this->isbn;
    }

    public function setISBN($isbn) {
        $this->isbn = $isbn;
    }

    public function getEdicao() {
        return $this->edicao;
    }

    public function setEdicao($edicao) {
        $this->edicao = $edicao;
    }

    public function getAno() {
        return $this->ano;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }

    public function getUpload() {
        return $this->upload;
    }

    public function setUpload($upload) {
        $this->$upload = $upload;
    }

    public function getIdTbEditora() {
        return $this->tb_editora_id_tb_editora;
    }

    public function setIdTbEditora($tb_editora_id_tb_editora) {
        $this->tb_editora_id_tb_editora = $tb_editora_id_tb_editora;
    }

    public function getIdTbCategoria() {
        return $this->tb_categoria_id_tb_categoria;
    }

    public function setIdTbCategoria($tb_categoria_id_tb_categoria) {
        $this->tb_categoria_id_tb_categoria = $tb_categoria_id_tb_categoria;
    }

    public function getTituloLivroPorId($id) {
        $sql = "SELECT titulo FROM tb_livro WHERE idtb_livro = :id";
        $statement = Conexao::getInstance()->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $dados = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $dados[0]['titulo'];
    }

}

?>