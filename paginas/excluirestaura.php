<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 14/10/2016
 * Time: 16:39
 */
ini_set('default_charset','utf-8');
require_once ("../Usuario.php");
require_once ("../Paginas.php");
require_once ("../Estagio.php");
require_once ("../Estado.php");
session_start();

$usuario = Usuario::unserialize($_SESSION['ctism_user']);
if (!Paginas::checkPermissao($usuario,reset(explode('.',basename($_SERVER['SCRIPT_FILENAME']))))) {
    http_response_code(403);
    if ( basename($_SERVER['SCRIPT_NAME']) != 'getpage.php' ) {
        header( 'Location: index.php' );
    }
    die('Você não pode acessar esta página.');
}

$estagio = Estagio::getById($_REQUEST['cod']);
if ($_REQUEST['acao'] == 'e') { //excluir (marcar estado excluido)
    $estagio->setEstado(Estado::EXCLUIDO);
    $saveOk = $estagio->save();
    echo $saveOk ? 'Estágio excluido com sucesso' : 'Erro ao excluir o estágio';
} else { //restaurar marcar o estado_anterior como estado atual
	$estagio->setEstado($estagio->getEstadoAnterior());
	$saveOk = $estagio->save();
	echo $saveOk ? 'Estágio restaurado com sucesso!' : 'Erro ao restaurar o estágio';
}