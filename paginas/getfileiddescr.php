<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 04/08/16
 * Time: 16:03
 */
ini_set('default_charset','utf-8');
session_start();
require_once ("../Usuario.php");
require_once ("../Estagio.php");
require_once ("../Arquivo.php");
require_once ("../Grupos.php");

$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$arquivo = Arquivo::getById($_REQUEST['codarq']);
$estagio = Estagio::getById($arquivo->getIdEstagio());

$permissao = ($usuario->hasGroup(Grupos::SSI) || $usuario->hasGroup(Grupos::DREC_SECRETARIA)) ||
    ($usuario->getUid() == $estagio->getProfessor()) ||
    ($usuario->getUid() == $estagio->getAluno() && $arquivo->isVisivelAluno());

$permissao or die('Arquivo não autorizado');
$json = '{ "id":' . $arquivo->getId() . ',"descr":"' . $arquivo->getDescricao() . '"}';
echo $json;