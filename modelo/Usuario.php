<?php

class Usuario {
    private $id_tb_usuario;
    private $nomeUsuario;
    private $tipo;
    private $senha;
    private $email;

    public function __construct($id_tb_usuario, $nomeUsuario, $tipo, $senha, $email) {
        $this->id_tb_usuario = $id_tb_usuario;
        $this->nomeUsuario = $nomeUsuario;
        $this->tipo = $tipo;
        $this->senha = $senha;
        $this->email = $email;
    }

    public function getIdTbUsuario() {
        return $this->id_tb_usuario;
    }

    public function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setIdTbUsuario($id_tb_usuario) {
        $this->id_tb_usuario = $id_tb_usuario;
    }

    public function setNomeUsuario($nomeUsuario) {
        $this->nomeUsuario = $nomeUsuario;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public static function encodePassword($pass) {
        $op = ['cost'=>12];
        return password_hash($pass, PASSWORD_BCRYPT, $op);
    }

    public static function tipoUsuario() {
        return [
            [
                'valor' => 0,
                'nome' => 'Administrador'
            ],
            [
                'valor' => 1,
                'nome' => 'Biblioteca'
            ],
            [
                'valor' => 2,
                'nome' => 'Comum'
            ],
        ];
    }

    public static function getTipoText($valor) {
        switch ($valor) {
            case 0:
                return 'Administrador';
                break;
            case 1:
                return 'Biblioteca';
                break;
            case 2:
                return 'Comum';
                break;
        }
    }

}

?>