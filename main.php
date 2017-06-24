<!DOCTYPE html>
<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 11/04/2016
 * Time: 15:29
 */
//echo phpinfo(); die();
//echo get_current_user();die();
//ini_set('error_reporting',E_ALL);
//ini_set('default_charset','utf-8');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
session_start();
require_once ("Usuario.php");
$usuario = Usuario::unserialize($_SESSION['ctism_user']);
//session_destroy();session_commit();die();
if (!isset($_SESSION['ctism_user'])) {
    header('Location: login.php');
}
?>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/eqheight.css">
    <link rel="stylesheet" href="css/main.css">


    <title></title>
</head>
<body>
    <div class="container-fluid">

        <div class="row"> <!--header and content -->
            <div id="div-left-menu" class="col-xs-1 col-sm-2">
                <ul class="nav nav-pills nav-stacked">
                    <?
                    require_once("Paginas.php");
                    require_once ("Usuario.php");
                    $paginas = new Paginas();
                    $permitidas = $paginas->getAllowedPages($usuario);
                    for ($i=0; $i<sizeof($permitidas); $i++) {
                        $activeStr = ( $i == 0 && empty($_GET['pg']) || $_GET['pg'] == $permitidas[$i]['id'] ) ? ' active ' : '';
                        echo "<li class=\"li-nav $activeStr\" cod=\"{$permitidas[$i]['id']}\"><a>{$permitidas[$i]['nome']}</a></li>";
                    }
                    ?>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </div>
            <div class="col-xs-11 col-sm-10">
                <div class="row" id="div-header">
                    <div class="col-xs-10 vertical-middle" >
                        <div class="row">
                            <h4>Bem-vindo(a), <strong><?=$usuario->getFullName()?></strong></h4>
                        </div>
                        <div class="row">
                            <h1> <?
                                require_once ("ConfigClass.php");
                                echo ConfigClass::sysName;
                            ?> </h1>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <img src="./img/ctism.png" class="img-responsive" id="ctism-logo">
                    </div>
                </div>
                <div id="div-content"></div>
            </div>
        </div>
        <div class="navbar navbar-fixed-bottom rodape">
            <p class="pull-left">ssi@ctism.ufsm.br</p>
            <p class="pull-right">2017</p>
        </div>

    </div>
    <div id="modal-carregando" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="div-load"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-error" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading"> Erro! </div>
                <div class="modal-body">
                    <p id="p-err-message">Erro!</p>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-mensagem" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Mensagem
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="modal-mensagem-body" class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-confirm" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirma&ccedil;&atilde;o</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <!--<div class="btn-group"> -->
                    <button type="button" id="btn-cancela-ok" class="btn btn-danger">N&atilde;o</button>
                    <button type="button" id="btn-confirm-ok" class="btn btn-success">Sim</button>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</body>
<script type="application/javascript" language="javascript" src="js/jquery/jquery-2.2.1.min.js"></script>
<script type="application/javascript" language="javascript" src="js/jquery/jquery.mask.min.js"></script>
<script type="application/javascript" language="javascript" src="js/bootstrap/bootstrap.min.js"></script>
<script type="application/javascript" language="javascript" src="js/main-js.js"></script>
</html>