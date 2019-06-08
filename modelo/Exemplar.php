<?php

class Exemplar {

    private $id_tb_exemplar;
    private $tipoExemplar;
    private $id_tb_livro;

    public function __construct($id_tb_exemplar, $tipoExemplar, $id_tb_livro) {
        $this->id_tb_exemplar = $id_tb_exemplar;
        $this->tipoExemplar = $tipoExemplar;
        $this->id_tb_livro = $id_tb_livro;
    }

    public function getIdTbExemplar() {
        return $this->id_tb_exemplar;
    }

    public function setIdTbExemplar($id_tb_exemplar) {
        $this->id_tb_exemplar = $id_tb_exemplar;
    }

    public function getTipoExemplar() {
        return $this->tipoExemplar;
    }

    public function setTipoExemplar($tipoExemplar) {
        $this->tipoExemplar = $tipoExemplar;
    }

    public function getTbLivroIdTbLivro() {
        return $this->id_tb_livro;
    }

    public function setTbLivroIdTbLivro($id_tb_livro) {
        $this->id_tb_livro = $id_tb_livro;
    }

    public static function getNomeTipoExemplar($tipoExemplar) {
        switch ($tipoExemplar) {
            case '0':
                return 'Circular';
                break;
            
            case '1':
                return 'Não Circular';
                break;
            
            default:
                return '---';
                break;
        }
    }

}

?>