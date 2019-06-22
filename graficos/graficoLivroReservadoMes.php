<?php
// header ('charset=UTF-8');
require_once "../PHPlot/phplot/phplot.php";
require_once "../db/Conexao.php";
require_once "../functions/functions.php";

#Instancia o objeto e setando o tamanho do grafico na tela
$grafico = new PHPlot(600,600);

$grafico->SetYTitle("Quantidade de livros reservados");
$mesAtual = date('Y-m');
$mesPassado = date('Y-m', strtotime('-1 months'));
$mesRetrasado = date('Y-m', strtotime('-2 months'));

$sql = "
    SELECT 
        (SELECT count(id_reserva) WHERE dataReserva LIKE :mesAtual) AS mesAtual, 
        (SELECT count(id_reserva) WHERE dataReserva LIKE :mesPassado) AS mesPassado,
        (SELECT count(id_reserva) WHERE dataReserva LIKE :mesRetrasado) AS mesRetrasado
    FROM
        tb_reserva
";
$statement = Conexao::getInstance()->prepare($sql);
$statement->bindValue("mesAtual", $mesAtual."%");
$statement->bindValue("mesPassado", $mesPassado."%");
$statement->bindValue("mesRetrasado", $mesRetrasado."%");
$statement->execute();
$retorno = $statement->fetch(PDO::FETCH_ASSOC);

$mesAtual = date('m');
$mesPassado = date('m', strtotime('-1 months'));
$mesRetrasado = date('m', strtotime('-2 months'));

$dados = array(
    array(mesPortugues($mesRetrasado), $retorno['mesRetrasado']),
    array(mesPortugues($mesPassado), $retorno['mesPassado']),
    array(mesPortugues($mesAtual), $retorno['mesAtual'])
);

#Definimos os dados do gráfico

$grafico->SetDataValues($dados);

#Neste caso, usariamos o gráfico em barras
$grafico->SetPlotType("bars");

#Exibimos o gráfico
$grafico->DrawGraph();