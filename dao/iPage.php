<?php
require_once "iDAO.php";
require_once 'db/Conexao.php';
interface iPage extends iDAO
{
    public function tabelapaginada();
}
?>