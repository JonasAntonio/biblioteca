<?php

    class Emprestimo {
        private $tb_usuario_id_tb_usuario;
        private $tb_exemplar_id_tb_exemplar;
        private $dataEmprestimo;
        private $dataDevolucao;
        private $observacao;

        public function __construct($tb_usuario_id_tb_usuario, $tb_exemplar_id_tb_exemplar, $dataEmprestimo, $dataDevolucao, $observacao) {
            $this->tb_usuario_id_tb_usuario = $tb_usuario_id_tb_usuario;
            $this->tb_exemplar_id_tb_exemplar = $tb_exemplar_id_tb_exemplar;
            $this->dataEmprestimo = $dataEmprestimo;
            $this->dataDevolucao = $dataDevolucao;
            $this->observacao = $observacao;
        }

        public function getIdUsuario() {
            return $this->tb_usuario_id_tb_usuario;
        }
    
        public function setIdUsuario($tb_usuario_id_tb_usuario) {
            $this->tb_usuario_id_tb_usuario = $tb_usuario_id_tb_usuario;
        }

        public function getIdExemplar() {
            return $this->tb_exemplar_id_tb_exemplar;
        }
    
        public function setIdExemplar($tb_exemplar_id_tb_exemplar) {
            $this->tb_exemplar_id_tb_exemplar = $tb_exemplar_id_tb_exemplar;
        }
    
        public function getDataEmprestimo() {
            return $this->dataEmprestimo;
        }
    
        public function setDataEmprestimo($dataEmprestimo) {
            $this->dataEmprestimo = $dataEmprestimo;
        }

        public function getDataDevolucao() {
            return $this->dataDevolucao;
        }
    
        public function setDataDevolucao($dataDevolucao) {
            $this->dataDevolucao = $dataDevolucao;
        }

        public function getObservacao() {
            return $this->observacao;
        }
    
        public function setObservacao($observacao) {
            $this->observacao = $observacao;
        }
    }

?>