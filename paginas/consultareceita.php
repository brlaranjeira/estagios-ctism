<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 13/12/16
 * Time: 15:06
 */

ini_set('default_charset','utf-8');
session_start();
require_once ("../Empresa.php");
require_once ("../Paginas.php");
require_once ("../Usuario.php");

$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$permissao = Paginas::checkPermissao($usuario,reset(explode('.',basename($_SERVER['PHP_SELF']))));
if (!$permissao) {
    http_response_code(403);
    echo 'Você não pode acessar esta página.';
    die();
}
$protocol = 'https';
$host = 'www.receitaws.com.br';
$path = '/v1/cnpj/' . $_REQUEST['cnpj'];
$url = "$protocol://$host$path";
$json = file_get_contents($url);
echo $json;
