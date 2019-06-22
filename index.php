<?php
require_once "view/template.php";
template::header();
template::sidebar();
template::mainpanel();
?>
    <style>
        .responsive-img {
            width: 100%;
            height: auto;
        }
    </style>
    <div class='content' xmlns="http://www.w3.org/1999/html">
        <div class='container-fluid'>
            <div class='row'>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <p>Livros reservados nos últimos 3 meses</p>
                        <img class="responsive-img" src="graficos/graficoLivroReservadoMes.php" />
                    </div>
                    <div class="col-md-6">
                        <p>Livros emprestados nos últimos 3 meses</p>
                        <img class="responsive-img" src="graficos/graficoLivroEmprestadoMes.php" />
                    </div>
                </div>
            </div>
            <br>
            <!-- <div class='row'>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <p>Livros reservados nos últimos 3 meses por categoria</p>
                        <img src="PHPlot/graficoLivroEmprestadoCategoria.php" />
                    </div>
                    <div class="col-md-6">
                        <p>Livros emprestados nos últimos 3 meses por categoria</p>
                        <img src="PHPlot/graficoLivroReservadoCategoria.php" />
                    </div>
                </div>
            </div> -->


        </div>
    </div>

<?php
template::footer();
?>