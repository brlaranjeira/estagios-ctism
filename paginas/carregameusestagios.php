<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 15/06/2016
 * Time: 10:18
 */

ini_set('default_charset','utf-8');
require_once ("../Usuario.php");
require_once ("../Grupos.php");
require_once ("../Estagio.php");
require_once ("../Estado.php");
require_once ("../Anotacao.php");

session_start();
$usuario = Usuario::unserialize($_SESSION['ctism_user']);
if ( !$usuario->hasGroup( Grupos::ALUNOS ) && !$usuario->hasGroup( Grupos::PROFESSORES ) && !$usuario->hasGroup( Grupos::SSI ) && !$usuario->hasGroup( Grupos::DREC_SECRETARIA ) ) {
	http_response_code(403);
	if ( basename($_SERVER['SCRIPT_NAME']) != 'getpage.php' ) {
		header( 'Location: index.php' );
	}
	die('Você não pode acessar esta página.');
}

$tipos = array();
$operadores = array();
$params = array();
if (isset($_REQUEST['filters'])) {
    foreach ( $_REQUEST['filters'] as $filter ) {
        switch ($filter['name']) {
            case 'filtro_tipo[]':
            	if (empty($filter['value'])) {
            		$tipos[] = '';
				} else {
					$emp = preg_match('/^empresa_/',$filter['value']);
					$tipos[] = $emp ? ' em.' . substr($filter['value'],strpos($filter['value'],'_')+1) : ' e.' . $filter['value'];
				}
                break;
            case 'filtro_operador[]':
                 $operadores[] = ' ' . $filter['value'];
                break;
            case 'filtro_param[]':
                $params[] = $filter['value'];
                break;
        }
        $first = false;
    }
}

$strFiltro = '';
require_once ("../Utils.php");
for ($i = 0; $i < sizeof($tipos); $i++ ) {
	if (!empty($tipos[$i])) {
		$strFiltro .= empty($strFiltro) ? ' WHERE ' : ' AND ';
		$strFiltro .= trim($operadores[$i]) == 'like' ? 'UPPER(' . $tipos[$i] . ') ' : $tipos[$i] . ' ';
		$strFiltro .= $operadores[$i] . ' ';
		if (trim($tipos[$i]) == 'e.data_inicio' || trim($tipos[$i] == 'e.data_fim')) {
			$val = Utils::BRDateToUSDate($params[$i]);
		} elseif (strstr($tipos[$i],'cnpj')) {
			$val = str_replace(array('.','-','/'),"",$params[$i]);
		} else {
			$val =  $params[$i];
		}
		$strFiltro .= preg_match(trim($tipos[$i]), '/^id/') ? "$val" : trim($operadores[$i]) == 'like' ? "UPPER(\"%$val%\")" : "\"$val\"";
	}
}
$strFiltro .= ' ';

switch ( $_REQUEST['tp'] ) {
	case 'aluno':
		$estagios = Estagio::getAllFromAluno($usuario);
		break;
	case 'professor':
		$estagios = Estagio::getAllFromProfessor($usuario);
		break;
	case 'super':
		$estagios = Estagio::getAllByEstado(Estado::AGUARD_DEFER);
		break;
    case 'all':
        $estagios = Estagio::getAll($_REQUEST['criterion'],$_REQUEST['sort'],$strFiltro);
        break;
	default:
		$estagios = array();
}

$toEcho = '{"estagios":[';
$primeiroEstagio = true;

foreach ( $estagios as $estagio ) {
	$toEcho .= (!$primeiroEstagio) ? ',' : '';
	$primeiroEstagio = false;
	$toEcho .= $estagio->asJSON( $_REQUEST['tp'] == 'aluno' );
}
$toEcho .= ']}';
echo $toEcho;
