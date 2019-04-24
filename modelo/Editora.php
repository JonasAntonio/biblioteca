<?php

class Editora {
    private $id_tb_editora;
    private $nomeEditora;

    public function __construct($id_tb_editora, $nomeEditora) {
        $this->id_tb_editora = $id_tb_editora;
        $this->nomeEditora = $nomeEditora;
    }

    public function getIdTbEditora() {
        return $this->id_tb_editora;
    }

    public function setIdTbEditora($id_tb_editora) {
        $this->id_tb_editora = $id_tb_editora;
    }

    public function getNomeEditora() {
        return $this->nomeEditora;
    }

    public function setNomeEditora($nomeEditora) {
        $this->nomeEditora = $nomeEditora;
    }
}

?>