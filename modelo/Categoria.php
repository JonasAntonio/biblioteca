<?php

class Categoria {
    private $idtb_categoria;
    private $nomeCategoria;

    public function __construct($idtb_categoria, $nomeCategoria) {
        $this->idtb_categoria = $idtb_categoria;
        $this->nomeCategoria = $nomeCategoria;
    }

    public function getIdTbCategoria() {
        return $this->idtb_categoria;
    }

    public function setIdTbCategoria($idtb_categoria) {
        $this->idtb_categoria = $idtb_categoria;
    }

    public function getNomeCategoria() {
        return $this->nomeCategoria;
    }

    public function setNomeCategoria($nomeCategoria) {
        $this->nomeCategoria = $nomeCategoria;
    }
}

?>