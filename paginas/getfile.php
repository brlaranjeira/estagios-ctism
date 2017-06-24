<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21/06/16
 * Time: 16:42
 */
ini_set('default_charset','utf-8');
require_once ("../Usuario.php");
require_once ("../Arquivo.php");
require_once ("../Estagio.php");
require_once ("../Grupos.php");
require_once ("../ConfigClass.php");
require_once ("../Utils.php");

session_start();
$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$codarq = $_REQUEST['codarq'];
$arquivo = Arquivo::getById($codarq);
$estagio = Estagio::getById($arquivo->getIdEstagio());
$permissao = ($usuario->hasGroup(Grupos::SSI) || $usuario->hasGroup(Grupos::DREC_SECRETARIA) ) ||
    ($estagio->getAluno() == $usuario->getUid() && $arquivo->isVisivelAluno()) ||
    $estagio->getProfessor() == $usuario->getUid();

if (!$permissao) {
    http_response_code(403);
    die('Você não pode acessar esta página.');
}
header('Content-Disposition: attachment; filename="'. substr(strstr($arquivo->getCaminho(),'.'),1) . '"');
$extensao = end(explode('.',$arquivo->getCaminho()));
$mime_t = Utils::getMIMEType($extensao);
header('Content-Type: ' . $mime_t);
$fullPath = ConfigClass::diretorioArquivos . '/' . $arquivo->getCaminho();
readfile($fullPath);