<?php

    class Reserva {
        private $id_reserva;
        private $id_usuario;
        private $dataReserva;
        private $dataVencimento;
        private $observacao;
        private $status;

        public function __construct($id_reserva, $id_usuario, $dataReserva, $dataVencimento, $observacao, $status) {
            $this->id_reserva = $id_reserva;
            $this->id_usuario = $id_usuario;
            $this->dataReserva = $dataReserva;
            $this->dataVencimento = $dataVencimento;
            $this->observacao = $observacao;
            $this->status = $status;
        }

        public function getIdReserva() {
            return $this->id_reserva;
        }
    
        public function setIdReserva($id_reserva) {
            $this->id_reserva = $id_reserva;
        }

        public function getIdUsuario() {
            return $this->id_usuario;
        }
    
        public function setIdUsuario($id_usuario) {
            $this->id_usuario = $id_usuario;
        }
    
        public function getDataReserva() {
            return $this->dataReserva;
        }
    
        public function setDataReserva($dataReserva) {
            $this->dataReserva = $dataReserva;
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

        public function getStatus() {
            return $this->status;
        }
    
        public function setStatus($status) {
            $this->status = $status;
        }
    }

?>