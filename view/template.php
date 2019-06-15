<?php
require_once "db/Conexao.php";
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 02/03/2018
 * Time: 11:23
 */
class Template
{
    public static function header() {
        require_once "functions/functions.php";
        session_start();
        if((!isset ($_SESSION['login'])) and (!isset ($_SESSION['senha'])))
        {
            unset($_SESSION['login']);
            unset($_SESSION['senha']);
            header('location:login.php');
        } 
        $cmd = Conexao::getInstance()->prepare("SELECT * FROM tb_usuario WHERE email = :email");
        $cmd->bindValue(":email", $_SESSION['login']);
        $cmd->execute();
        $rs = $cmd->fetch(PDO::FETCH_ASSOC);
        
        $_SESSION['id_usuario'] = $rs['idtb_usuario'];
        $_SESSION['nomeUsuario'] = $rs['nomeUsuario'];
        $_SESSION['tipo_usuario'] = $rs['tipo'];
        $logado = $_SESSION['login'];
        
        ?>
        <!doctype html>
        <html lang='en'>
        <head>
            <meta charset='utf-8' />
            <link rel='icon' type='image/png' sizes='96x96' href='assets/img/favicon.jpg'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
            <title>Biblioteca</title>
            <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
            <meta name='viewport' content='width=device-width' />
            <!-- Bootstrap core CSS     -->
            <link href='assets/css/bootstrap.min.css' rel='stylesheet' />
            <!-- Animation library for notifications   -->
            <link href='assets/css/animate.min.css' rel='stylesheet'/>
            <!--  Paper Dashboard core CSS    -->
            <link href='assets/css/paper-dashboard.css' rel='stylesheet'/>
            <!--  Fonts and icons     -->
            <link href='http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' rel='stylesheet'>
            <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
            <link href='assets/css/themify-icons.css' rel='stylesheet'>
            <link href='assets/css/bootstrap-multiselect.css' rel='stylesheet'>
            <style>
                .multiselect-native-select > .btn-group {
                    /* background-color: #fffcf5; */
                    /* border-radius: 4px; */
                    /* color: #66615b; */
                    /* font-size: 14px; */
                    /* transition: background-color 0.3s ease 0s; */
                    /* padding: 7px 18px; */
                    /* height: 40px; */
                    /* -webkit-box-shadow: none; */
                    /* box-shadow: none; */
                }
            </style>
        </head>
        <body>
    <?php }
    public static function footer() { ?>
                <footer class="footer">
                    <div class="container-fluid">
                        <nav class="pull-left">
                            <ul>
                                <li>
                                    <a href="http://github.com/JonasAntonio" target='_blank'>
                                        Jonas Vicente
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <div class="copyright pull-right">
                            &copy; <script>document.write(new Date().getFullYear())</script>, template made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com" target='_blank'>Creative Tim</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        </body>
            <!--   Core JS Files   -->
            <script src="assets/js/jquery.js" type="text/javascript"></script>
            <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
            <!--  Checkbox, Radio & Switch Plugins -->
            <script src="assets/js/bootstrap-checkbox-radio.js"></script>
            <script src="assets/js/bootstrap-multiselect.js"></script>
            <script type="text/javascript">
                j = jQuery.noConflict();
                j(document).ready(function() {
                    j('.multiselect').multiselect({
                        enableFiltering: true,
                        includeSelectAllOption: true,
                        buttonWidth: '100%',
                        maxHeight: 'auto',
                        nonSelectedText: 'Selecione alguma opção',
                        filterPlaceholder: 'Pesquise aqui',
                        inheritClass: true,
                        enableCaseSensitive: true
                    });
                });
            </script>
        </html>
    <?php 
    }
    public static function sidebar() { ?>
        <div class="wrapper">
        <div class="sidebar" data-background-color="black" data-active-color="info">
        <!--
            Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
            Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
        -->
        <div class="sidebar-wrapper">
            <div class="logo">
                <!-- <a href='index.php'><img src="assets/img/biblioteca-digital-inoveduc.jpg" height="150" width="200"></a> -->
                <h4>Biblioteca</h4>
                <small></small>
                <a class='btn btn-info' href="logout.php">Logout</a>
            </div>
            <ul class="nav">
                <li class="<?=$_SESSION['active_window'] == 'autores' ? 'active' : ''?>">
                    <a href='autores.php'>
                        <i class="ti-user"></i>
                        <p>Autores</p>
                    </a>
                </li>
                <li class="<?=$_SESSION['active_window'] == 'editoras' ? 'active' : ''?>">
                    <a href = 'editoras.php' >
                        <i class="ti-book"></i >
                        <p > Editoras</p >
                    </a >
                </li >
                <?php if($_SESSION['tipo_usuario'] == 0 || $_SESSION['tipo_usuario'] == 1) { ?>
                    <li class="<?=$_SESSION['active_window'] == 'categorias' ? 'active' : ''?>">
                        <a href = 'categorias.php' >
                            <i class="ti-book"></i >
                            <p > Categorias</p >
                        </a >
                    </li >
                    <li class="<?=$_SESSION['active_window'] == 'exemplares' ? 'active' : ''?>">
                        <a href='exemplares.php'>
                            <i class="ti-user"></i>
                            <p>Exemplares</p>
                        </a>
                    </li>
                <?php } ?>

                <li class="<?=$_SESSION['active_window'] == 'livros' ? 'active' : ''?>">
                    <a href='livros.php'>
                        <i class="ti-book"></i>
                        <p>Livros</p>
                    </a>
                </li>
                <?php if($_SESSION['tipo_usuario'] == 0 || $_SESSION['tipo_usuario'] == 4) { ?>
                    <li class="<?=$_SESSION['active_window'] == 'emprestimos' ? 'active' : ''?>">
                        <a href='emprestimos.php'>
                            <i class="ti-user"></i>
                            <p>Empréstimos</p>
                        </a>
                    </li>
                    <li class="<?=$_SESSION['active_window'] == 'reservas' ? 'active' : ''?>">
                        <a href='reservas.php'>
                            <i class="ti-user"></i>
                            <p>Reservas</p>
                        </a>
                    </li>
                    <li class="<?=$_SESSION['active_window'] == 'usuarios' ? 'active' : ''?>">
                        <a href='usuarios.php'>
                            <i class="ti-user"></i>
                            <p>Usuários</p>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    
    <?php 
    }
    public static function mainpanel()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $data = date("F j, Y"); ?>
        <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Home</a>
                </div>
            </div>
        </nav>
    <?php
    }
}