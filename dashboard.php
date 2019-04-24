<?php

require_once "view/template.php";

$template = new Template();

$template->header();

$template->sidebar();

$template->mainpanel();

?>

        <div class="content">
            <div class="container-fluid">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Análise de Medidores</h4>
                                <p class="category">Registros/Medidor</p>
                            </div>
                            <div class="content">
                                <div id="chartHours">
                                    <img src="images/GraficoEx.jpg" height="300px">
                                </div>
                            </div>
                            <div class="t">
                                <div id="chartHours" >
                                </div>
                                <div class="footer">
                                    <hr>
                                    <div class="stats">
                                        <a href="#">Exportar em PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <?php

        $template->footer();

        ?>

