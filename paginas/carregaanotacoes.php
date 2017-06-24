<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 28/06/16
 * Time: 14:39
 */

ini_set('default_charset','utf-8');
require_once ("../Usuario.php");
require_once ("../Anotacao.php");
require_once ("../Estagio.php");
require_once ("../Grupos.php");


session_start();
$usuario = Usuario::unserialize($_SESSION['ctism_user']);

if (isset($_REQUEST['estagio'])) {
    $estagio = Estagio::getById( $_REQUEST['estagio'] );
} elseif (isset($_REQUEST['arquivo'])) {
    $arquivo = Arquivo::getById($_REQUEST['arquivo']);
}

if (!isset($estagio) ||$usuario->getUid() != $estagio->getAluno() && $usuario->getUid() != $estagio->getProfessor() && !$usuario->hasGroup(Grupos::SSI) && !$usuario->hasGroup(Grupos::DREC_SECRETARIA)) {
    http_response_code(403);
    die('Voc&ecirc; n&atilde;o pode acessar esta p&aacute;gina');
}
$isAluno = $estagio->getAluno() == $usuario->getUid();
$anotacoes = isset($_REQUEST['estagio']) ? Anotacao::getAllForEstagio($estagio->getId(), $isAluno ) : Anotacao::getAllForArquivo($arquivo->getId());
$ret = '{"anotacoes":[';
$first = true;
foreach ( $anotacoes as $anotacao ) {
    $ret .= $first ? '' : ',';
    $ret .= $anotacao;
    $first = false;
}
$ret .= ']}';
echo $ret;

