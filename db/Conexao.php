<?php

require_once("configDB.php");

class Conexao {
    private static $pdo;
    private function __construct()
    {
    self::getInstace();
    }

    public static function getInstance(){
    if (!isset(self::$pdo)){
		try{
			self::$pdo = new PDO(DRIVER.":host=" . HOSTNAME . "; dbname=" . DBNAME . "; chars=". CHARSET . ";", USER, PASS, []);
		}catch (PDOException $e){
			print "Erro: ". $e->getMessage();
		}
    }
        return self::$pdo;
    }
}