<?php
require_once "../PHPlot/phplot/phplot.php";
require_once "../db/Conexao.php";
require_once "../functions/functions.php";

#Instancia o objeto e setando o tamanho do grafico na tela
$grafico = new PHPlot(600,600);

$grafico->SetYTitle("Quantidade de livros emprestados");
$mesAtual = date('n');
$mesPassado = date('n', strtotime('-1 months'));
$mesRetrasado = date('n', strtotime('-2 months'));

$sql = "
    SELECT 
        (SELECT count(id_emprestimo) WHERE MONTH(dataEmprestimo) = :mesAtual) AS mesAtual, 
        (SELECT count(id_emprestimo) WHERE MONTH(dataEmprestimo) = :mesPassado) AS mesPassado,
        (SELECT count(id_emprestimo) WHERE MONTH(dataEmprestimo) = :mesRetrasado) AS mesRetrasado
    FROM
        tb_emprestimo
";
$statement = Conexao::getInstance()->prepare($sql);
$statement->bindValue("mesAtual", $mesAtual);
$statement->bindValue("mesPassado", $mesPassado);
$statement->bindValue("mesRetrasado", $mesRetrasado);
$statement->execute();
$retorno = $statement->fetch(PDO::FETCH_ASSOC);

$dados = array(
    array(mesPortugues($mesRetrasado), $retorno['mesRetrasado']),
    array(mesPortugues($mesPassado), $retorno['mesPassado']),
    array(mesPortugues($mesAtual), $retorno['mesAtual'])
    // array('1950', 40, 95, 20),
    // array('1960', 45, 85, 30),
    // array('1970', 50, 80, 40),
    // array('1980', 48, 77, 50),
    // array('1990', 38, 72, 60),
    // array('2000', 35, 68, 70),
    // array('2010', 30, 67, 80),
);

#Definimos os dados do gráfico

$grafico->SetDataValues($dados);

#Neste caso, usariamos o gráfico em barras
$grafico->SetPlotType("bars");

#Exibimos o gráfico
$grafico->DrawGraph();