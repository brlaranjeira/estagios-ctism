<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 25/05/2016
 * Time: 17:15
 */
ini_set('default_charset','utf-8');
session_start();
require_once ("../Usuario.php");
require_once ("../Estagio.php");
require_once ("../Grupos.php");
$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$estagio = Estagio::getById($_REQUEST['idestagio']);

if ( $usuario->getUid() != $estagio->getAluno() && $usuario->getUid() != $estagio->getProfessor() && !$usuario->hasGroup(Grupos::SSI) && !$usuario->hasGroup(Grupos::DREC_SECRETARIA) ) {
	http_response_code(403);
	die('Você não pode acessar esta página.');
}

require_once ("../Arquivo.php");
try {
	$visivelAluno = !isset($_REQUEST['arq_inv']) || $_REQUEST['arq_inv'] != 'on' || $usuario->getUid() == $estagio->getAluno();
	$arq = new Arquivo( $_FILES['arq']['name'], $estagio->getId(), $_REQUEST['descrarq'], $visivelAluno );
	$arq->moveToDir($_FILES['arq']['tmp_name']);
	$arq->saveOrUpdate();
	echo 'Arquivo enviado com sucesso!';
} catch (Exception $ex) {
	http_response_code(500);
	echo 'Ocorreu um erro interno. Tente novamente mais tarde ou contate o Setor de Informática.';
}


