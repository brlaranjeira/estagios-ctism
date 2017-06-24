<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 18/11/16
 * Time: 18:06
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
$empresas = Empresa::getByCNPJ($_REQUEST['cnpj']);
if (isset($empresas) && !empty($empresas)) {
    echo $empresas[0]->asJSON();
}
echo '';