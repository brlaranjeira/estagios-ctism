<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/06/16
 * Time: 17:29
 */

session_start();
require_once ("../Usuario.php");
require_once ("../Estagio.php");
require_once ("../Grupos.php");
require_once ("../Estado.php");
$usuario = Usuario::unserialize( $_SESSION['ctism_user'] );
$estagio = Estagio::getById($_REQUEST['cod']);

if ( $estagio->getAluno() == $usuario->getUid() ) {
    unset( $_REQUEST['idaluno'] , $_POST['idaluno'] , $_GET['idaluno'], $_REQUEST['nota'] , $_POST['nota'], $_GET['nota'], $_REQUEST['empresas'], $_POST['empresas'], $_GET['empresas'] );
}
if ($estagio->getEstado()->getId() == Estado ::EM_DESENV) {
    unset( $_REQUEST['nota'] , $_POST['nota'], $_GET['nota'] );
}

//permissao
$permitido = !isset($_REQUEST['estado']) || $_REQUEST['estado'] != Estado::EXCLUIDO || $usuario->getUid() == $estagio->getAluno(); // somente o aluno pode excluir o proprio estagio
if (!$usuario->hasGroup(Grupos::DREC_SECRETARIA) && !$usuario->hasGroup(Grupos::SSI)) { //apenas precisamos ver as permissoes para quem nao esta nestes grupos
    if ( $usuario->hasGroup(Grupos::ALUNOS) || $usuario->hasGroup(Grupos::PROFESSORES) ) { //alunos e professores podem editar o estagio que pertence a eles
        if ($usuario->getUid() == $estagio->getAluno()) {
            //aluno soh pode mudar para alguns estados ou alterar dados basicos (que tambem vai alterar o estado para inicial)
            //aluno tambem nao pode mudar a nota
            if (isset($_REQUEST['nota']) || isset($_REQUEST['estado']) && $_REQUEST['estado'] != Estado::EXCLUIDO && $_REQUEST['estado'] != Estado::AGUARD_AVAL && $_REQUEST['estado'] != Estado::EM_DESENV) {
                $permitido = false;
            }
        } else if ($usuario->getUid() == $estagio->getProfessor()) { // professor pode mandar para qualquer estado, exceto deferido (somente drec/secretaria) e excluido (somente aluno)
            if ((!isset($_REQUEST['estado']) || $_REQUEST['estado'] == Estado::DEFERIDO) && !isset($_REQUEST['nota'])) {
                $permitido = false;
            }
        }
    } else { //se chegar aqui, nao eh drec/secretaria, nem ssi, nem aluno nem professor
        $permitido = false;
    }
}

if (!$permitido) { //mudando o estado
    http_response_code(403);
    header( 'Location: index.php' );
    die('Você não pode acessar esta página.');
}

if (!isset($_REQUEST['estado'])) {
    $usuario->getUid() == $estagio->getAluno() and $estagio->setEstado(Estado::EM_DESENV); //caso seja feita alguma mudança, o estado vai mudar para o inicialisset($_REQUEST['idcurso']) and $estagio->setCurso($_REQUEST['idcurso']);
    isset($_REQUEST['profresp']) and $estagio->setProfessor($_REQUEST['profresp']);
    isset($_REQUEST['dtfim']) and $estagio->setDtFim($_REQUEST['dtfim']);
    isset($_REQUEST['dtini']) and $estagio->setDtInicio($_REQUEST['dtini']);
    isset($_REQUEST['nota']) and $estagio->setNota($_REQUEST['nota']);
    isset($_REQUEST['local']) and $estagio->setLocal($_REQUEST['local']);
    isset($_REQUEST['idaluno']) and $estagio->setAluno($_REQUEST['idaluno']);
    isset($_REQUEST['supervisor']) and $estagio->setSupervisor($_REQUEST['supervisor']);
    isset($_REQUEST['idcurso']) and $estagio->setCurso($_REQUEST['idcurso']);
    isset($_REQUEST['ch']) and $estagio->setCargaHoraria($_REQUEST['ch']);
    isset($_REQUEST['obrigatorio']) and $estagio->setObrigatorio($_REQUEST['obrigatorio'] == 'on');
    $empresas = array();
    for ($i=0;$i<sizeof($_REQUEST['empresas']);$i++) {
		$nova =  $_REQUEST['empresas'][$i];
		$ch = $nova['carga_horaria'];
		$supervisor = $nova['supervisor'];
		$nova = Empresa::getById($nova['id']);
		$nova->setCargaHoraria($ch);
		$nova->setSupervisor($supervisor);
		$empresas[] = $nova;
	}
	$estagio->setEmpresas($empresas);
} else {
    $estagio->setEstado($_REQUEST['estado']);
    if ($_REQUEST['estado'] == Estado::DEFERIDO) {
        if ($estagio->isNotaOk()) {
            if ($estagio->getToken() == null) {
                $token = $estagio->generateToken();
                $out = $estagio->generateCertPDF($token);
                if (!$out) {
                    echo 'Ocorreu algum erro na modificação do estágio.';
                    http_response_code('500');
                    die();
                } else {
                    $estagio->setToken($token);
                }
            }
        }
    }
}

if ( $estagio->save() ) {
   echo 'Estágio modificado com sucesso!';
   if ($usuario->getUid() == $estagio->getAluno()) {
       //$usuario->getUid() == $estagio->getAluno()
       echo '<br/><strong></strong>';
   }
} else {
   echo 'Ocorreu algum erro na modificação do estágio.';
}