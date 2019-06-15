<?php

    class Emprestimo {
        private $id_emprestimo;
        private $id_usuario;
        private $dataEmprestimo;
        private $dataDevolucao;
        private $dataVencimento;
        private $observacao;

        public function __construct($id_emprestimo, $id_usuario, $dataEmprestimo, $dataDevolucao, $dataVencimento, $observacao) {
            $this->id_emprestimo = $id_emprestimo;
            $this->id_usuario = $id_usuario;
            $this->dataEmprestimo = $dataEmprestimo;
            $this->dataDevolucao = $dataDevolucao;
            $this->dataVencimento = $dataVencimento;
            $this->observacao = $observacao;
        }

        public function getIdEmprestimo() {
            return $this->id_emprestimo;
        }
    
        public function setIdEmprestimo($id_emprestimo) {
            $this->id_emprestimo = $id_emprestimo;
        }

        public function getIdUsuario() {
            return $this->id_usuario;
        }
    
        public function setIdUsuario($id_usuario) {
            $this->id_usuario = $id_usuario;
        }

        // public function getIdExemplar() {
        //     return $this->id_exemplar;
        // }
    
        // public function setIdExemplar($id_exemplar) {
        //     $this->id_exemplar = $id_exemplar;
        // }
    
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

        public function getDataVencimento() {
            return $this->dataVencimento;
        }
    
        public function setDataVencimento($dataVencimento) {
            $this->dataVencimento = $dataVencimento;
        }

        public function getObservacao() {
            return $this->observacao;
        }
    
        public function setObservacao($observacao) {
            $this->observacao = $observacao;
        }
    }

?>