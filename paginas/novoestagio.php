<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 25/05/2016
 * Time: 11:15
 */

ini_set('default_charset','utf-8');
require_once ("../Estagio.php");
require_once ("../Estado.php");
require_once ("../Usuario.php");
require_once ("../Grupos.php");

//require_once ("../Paginas.php");

session_start();
$usuario = Usuario::unserialize($_SESSION['ctism_user']);

if ( !$usuario->hasGroup(Grupos::SSI) && !$usuario->hasGroup(Grupos::DREC_SECRETARIA) ) {
	http_response_code(403);
	if ( basename($_SERVER['SCRIPT_NAME']) != 'getpage.php' ) {
		header( 'Location: index.php' );
	}
	die('Você não pode acessar esta página.');
}


require_once ("../Utils.php");
$dtIni = Utils::BRDateToUSDate($_POST['dtini']);
$dtFim = Utils::BRDateToUSDate($_POST['dtfim']);
$obrigatorio = isset($_POST['obrigatorio']) && $_POST['obrigatorio'] == 'on';

//$id, $aluno, $professor, $nota, $dt_inicio, $dt_fim, $estado, $curso, $obrigatorio, $empresas=array(), $estado_anterior = null
$empresas = array();
require_once ("../Empresa.php");
for ( $i = 0; $i < sizeof($_REQUEST['id-empresa']); $i++ ) {
    $emp = Empresa::getById($_REQUEST['id-empresa'][$i]);
    $emp->setSupervisor($_REQUEST['supervisor'][$i]);
    $emp->setCargaHoraria($_REQUEST['c_h'][$i]);
    $empresas[] = $emp;
}
$estagio = new Estagio(null,$_POST['alunoest'],$_POST['profresp'],null,$dtIni,$dtFim,Estado::EM_DESENV,$_POST['idcurso'],$obrigatorio,$empresas,null);
$saveOk = $estagio->save();

echo isset($saveOk) ? 'Estágio criado com sucesso' : 'Erro ao criar o estágio';
