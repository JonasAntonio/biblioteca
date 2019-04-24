<?php

    class Emprestimo {
        private $id_tb_usuario;
        private $id_tb_exemplar;
        private $dataEmprestimo;
        private $observacao;

        public function __construct($id_tb_usuario, $id_tb_exemplar, $dataEmprestimo, $observacao) {
            $this->id_tb_usuario = $id_tb_usuario;
            $this->id_tb_exemplar = $id_tb_exemplar;
            $this->dataEmprestimo = $dataEmprestimo;
            $this->observacao = $observacao;
        }

        public function getIdTbUsuario() {
            return $this->id_tb_usuario;
        }
    
        public function setIdTbUsuario($id_tb_usuario) {
            $this->id_tb_usuario = $id_tb_usuario;
        }

        public function getIdTbExemplar() {
            return $this->id_tb_exemplar;
        }
    
        public function setIdTbExemplar($id_tb_exemplar) {
            $this->id_tb_exemplar = $id_tb_exemplar;
        }
    
        public function getDataEmprestimo() {
            return $this->dataEmprestimo;
        }
    
        public function setDataEmprestimo($dataEmprestimo) {
            $this->dataEmprestimo = $dataEmprestimo;
        }

        public function getObservacao() {
            return $this->observacao;
        }
    
        public function setObservacao($observacao) {
            $this->observacao = $observacao;
        }
    }

?>