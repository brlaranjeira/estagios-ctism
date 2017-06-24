<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 16/12/2016
 * Time: 18:24
 */

ini_set('default_charset','utf-8');
session_start();
require_once ("../Paginas.php");
require_once ("../Usuario.php");
require_once ("../Grupos.php");

$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$permissao = Paginas::checkPermissao($usuario,reset(explode('.',basename($_SERVER['PHP_SELF']))));
if (!$permissao) {
	http_response_code(403);
	echo 'Você não pode acessar esta página.';
	die();
}

$todos = array();
switch ( $_REQUEST['tp'] ) {
	case 'aluno':
		$todos = Usuario::getAllFromGroups(array(Grupos::ALUNOS,Grupos::BOLSISTAS));
		break;
	case 'professor':
		$todos = Usuario::getAllFromGroups(Grupos::PROFESSORES);
		break;
	case 'id_curso':
		require_once ("../Curso.php");
		$todos = Curso::getAll();
		break;
    case 'id_estado':
        require_once ("../Estado.php");
        $todos = Estado::getAll();
        break;
}

$ret = '{ "todos": [';
$first = true;
foreach ( $todos as $elm ) {
    $tp = $_REQUEST['tp'] == 'id_curso' || $_REQUEST['tp'] == 'id_estado';
    $uid =  $tp ? $elm->getId() : $uid = $elm->getUid();
    $nome = $tp ? $elm->getDescricao() : $elm->getFullName() ;
    if (!$first) {
        $ret .= ',';
    }
    $ret .= '{"val": "' . $uid . '"';
    $ret .= ',"text": "' . $nome . '" }';
    $first = false;
}
$ret .= ']}';
echo $ret;