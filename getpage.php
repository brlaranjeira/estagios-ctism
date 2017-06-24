<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 12/04/2016
 * Time: 09:40
 */
ini_set('default_charset','utf-8');
session_start();
require_once ("Usuario.php");
$usuario = Usuario::unserialize($_SESSION['ctism_user']);
if(empty($usuario) ) {
    header('Location: login.php');
}
$pg = $_GET['pg'];
require_once("Paginas.php");
$paginas = new Paginas();
if ($paginas->checkPermissao($usuario,$pg)) {
    $pagFile = "paginas/$pg.php";
    if ( file_exists( $pagFile ) ){
        include( $pagFile );
    } else {
        http_response_code(404);
    }

} else {
    http_response_code(403);
    die('Você não pode acessar esta página.');
}
