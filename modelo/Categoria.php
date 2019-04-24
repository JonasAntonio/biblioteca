<?php

class Categoria {
    private $id_tb_categoria;
    private $nomeCategoria;

    public function __construct($id_tb_categoria, $nomeCategoria) {
        $this->id_tb_categoria = $id_tb_categoria;
        $this->nomeCategoria = $nomeCategoria;
    }

    public function getIdTbCategoria() {
        return $this->id_tb_categoria;
    }

    public function setIdTbCategoria($id_tb_categoria) {
        $this->$id_tb_categoria = $id_tb_categoria;
    }

    public function getNomeCategoria() {
        return $this->nomeCategoria;
    }

    public function setNomeCategoria($nomeCategoria) {
        $this->$nomeCategoria = $nomeCategoria;
    }
}

?>