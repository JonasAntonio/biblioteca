<?php

class Autor{

    private $id_livro_idtb_livro;
    private $tb_autores_idtb_autores;

    public function __construct($id_livro_idtb_livro, $tb_autores_idtb_autores) {
        $this->id_livro_idtb_livro = $id_livro_idtb_livro;
        $this->tb_autores_idtb_autores = $tb_autores_idtb_autores;
    }

    public function getIdLivroIdtbLivro() {
        return $this->id_livro_idtb_livro;
    }

    public function setIdLivroIdtbLivro($id_livro_idtb_livro) {
        $this->id_livro_idtb_livro = $id_livro_idtb_livro;
    }

    public function getTb_AutoresIdtbAutores() {
        return $this->tb_autores_idtb_autores;
    }

    public function setTb_AutoresIdtbAutores($tb_autores_idtb_autores) {
        $this->tb_autores_idtb_autores = $tb_autores_idtb_autores;
    }

}

?>