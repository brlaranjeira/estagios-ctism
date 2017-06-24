<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 24/06/16
 * Time: 10:13
 **/
ini_set('default_charset','utf-8');
require_once ("../Usuario.php");
require_once ("../Grupos.php");
require_once ("../Estagio.php");
require_once ("../Arquivo.php");
require_once ("../Anotacao.php");

session_start();
$usuario = Usuario::unserialize($_SESSION['ctism_user']);
//14h secretaria do curso - email ligia
$arquivo = isset($_REQUEST['arquivo']) ? Arquivo::getById($_REQUEST['arquivo']) : null;
$estagio = isset($_REQUEST['estagio']) ? Estagio::getById($_REQUEST['estagio']) : Estagio::getById($arquivo->getIdEstagio());
(!isset($arquivo) && !isset($estagio)) and die('arquivo ou estagio devem ser especificados');
$permissao = $usuario->hasGroup(Grupos::SSI) || $usuario->hasGroup(Grupos::DREC_SECRETARIA) || $usuario->getUid() == $estagio->getAluno() || $usuario->getUid() == $estagio->getProfessor();
if ($permissao) {
    $anotacao = new Anotacao($_REQUEST['msg'],$usuario,$arquivo,$estagio);
    $ins = $anotacao->saveOrUpdate();
    if (isset($ins)) {
      echo 'Anotação criada com sucesso!';
    } else {
        echo 'Ocorreu um problema.';
        http_response_code(500);
    }
} else {
    http_response_code(403);
    header( 'Location: index.php' );
}