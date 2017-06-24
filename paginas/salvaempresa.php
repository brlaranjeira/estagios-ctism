<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 28/11/16
 * Time: 16:54
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
    echo 'Você não pode registrar uma empresa. Contate o setor responsável.';
    die();
}

$empresa = new Empresa(null,$_REQUEST['cnpj'],$_REQUEST['rsoc'],$_REQUEST['cid'],$_REQUEST['cep'],$_REQUEST['nro'],$_REQUEST['bairro'],$_REQUEST['comp'],$_REQUEST['lograd']);
$empresa->save();
echo $empresa->getId();