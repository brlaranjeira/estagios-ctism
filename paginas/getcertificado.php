<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 27/07/16
 * Time: 16:59
 */
ini_set('default_charset','utf-8');
session_start();
require_once ("../Usuario.php");
require_once ("../Estagio.php");
require_once ("../Estado.php");
$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$codestagio = $_REQUEST['codestagio'];
$estagio = Estagio::getById($codestagio);
$permissao = $estagio->getEstado()->getId() == Estado::DEFERIDO && $estagio->getToken() != null &&
    ($estagio->getAluno() == $usuario->getUid() || $estagio->getProfessor() == $usuario->getUid());
if ( $permissao ) {
    $filePath = $estagio->getCertificadoPath();
    header('Content-Disposition: attachment; filename="certificado.pdf"');
    readfile($filePath);
} else {
    http_response_code(403);
}
