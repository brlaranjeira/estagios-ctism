<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 20/12/16
 * Time: 16:07
 */

ini_set('default_charset','utf-8');
session_start();
require_once ("../Paginas.php");
require_once ("../Usuario.php");
require_once ("../Grupos.php");
require_once ("../Empresa.php");
require_once ("../Estagio.php");

$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$permissao = Paginas::checkPermissao($usuario,reset(explode('.',basename($_SERVER['PHP_SELF']))));
if (!$permissao) {
    http_response_code(403);
    echo 'Você não pode acessar esta página.';
    die();
}

$estagio = Estagio::getById($_REQUEST['estagio']);
$empresas = $estagio->getEmpresas();
$ret = '{ "empresas": [';
$first = true;
foreach ( $empresas as $empresa ) {
    $ret .= $first ? '' : ',';
    $first = false;
    $ret .= $empresa->asJSON();
}
$ret .= ']}';
echo  $ret;