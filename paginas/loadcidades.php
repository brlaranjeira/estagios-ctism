<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 21/11/16
 * Time: 11:30
 */

ini_set('default_charset','utf-8');
session_start();
require_once ("../UF.php");
require_once ("../Cidade.php");
require_once ("../Paginas.php");
require_once ("../Usuario.php");

$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$permissao = Paginas::checkPermissao($usuario,reset(explode('.',basename($_SERVER['PHP_SELF']))));
if (!$permissao) {
    http_response_code(403);
    echo 'Você não pode acessar esta página.';
    die();
}

$uf = UF::getByID($_REQUEST['uf']);
$cidades = $uf->getCidades();
$ret = '{"cidades": [';
$first = true;
foreach ($cidades as $cidade) {
    if (!$first) {
        $ret .= ',';
    }
    $ret .= $cidade->asJSON();
    $first = false;
}
$ret .= ']}';
echo $ret;

