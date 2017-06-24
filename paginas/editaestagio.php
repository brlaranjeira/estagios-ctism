<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 25/05/2016
 * Time: 14:40
 */
ini_set('default_charset','utf-8');
require_once ("Usuario.php");
require_once ("Paginas.php");
require_once ("Grupos.php");
Paginas::forcaSeguranca();
require_once ("Estagio.php");
$estagio = Estagio::getById($_REQUEST['id']);
$usuario = Usuario::unserialize($_SESSION['ctism_user']);


if ( !$usuario->hasGroup(Grupos::DREC_SECRETARIA) && !$usuario->hasGroup(Grupos::SSI) && $estagio->getAluno() != $usuario->getUid() && $estagio->getProfessor() != $usuario->getUid() ) {
	http_response_code(403);
	if ( basename($_SERVER['SCRIPT_NAME']) != 'getpage.php' ) {
		header( 'Location: index.php' );
	}
	die('Você não pode acessar esta página.');
}
?>
<div id="div-btn-finalizar" class="pull-left">
	<? if ($estagio->getAluno() == $usuario->getUid() && $estagio->getEstado()->getId() == Estado::EM_DESENV) { ?>
		<button type="button" class="btn btn-success btn-alterar" cod="<?=$estagio->getId()?>" estado="<?=Estado::AGUARD_AVAL?>">Concluir e enviar ao professor</button>
	<? } elseif ($estagio->getProfessor() == $usuario->getUid() && $estagio->getEstado()->getId() == Estado::AGUARD_AVAL ) { ?>
		<button type="button" <?=$estagio->getNota() != null ? '' : ' disabled ' ?> class="btn btn-success btn-alterar"  cod="<?=$estagio->getId()?>" estado="<?=Estado::AGUARD_DEFER?>">Concluir avaliação</button>
	<? } elseif ( ($usuario->hasGroup(Grupos::DREC_SECRETARIA) || $usuario->hasGroup(Grupos::SSI)) &&  $estagio->getEstado()->getId() == Estado::AGUARD_DEFER ) { ?>
		<button type="button" class="btn btn-success btn-alterar" cod="<?=$estagio->getId()?>" estado="<?=Estado::DEFERIDO?>">Deferir</button>
	<? } ?>
</div>
<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#estagio-info-gerais">Informa&ccedil;&otilde;es Gerais</a></li>
	<li><a data-toggle="tab" href="#estagio-arquivos">Arquivos</a></li>
	<li><a data-toggle="tab" href="#estagio-anotacoes">Anota&ccedil;&otilde;es</a></li>
</ul>
<div class="clearfix"></div>

<div class="tab-content">
	<div id="estagio-info-gerais" class="tab-pane fade in active">
		<? include 'paginas/fragments/aba-info-gerais.php'; ?>
	</div>
	<div id="estagio-arquivos" class="tab-pane fade">
		<? include 'paginas/fragments/aba-arquivos.php'; ?>
	</div>
	<div id="estagio-anotacoes" class="tab-pane fade">
		<? include 'paginas/fragments/aba-anotacoes.php'; ?>
	</div>

	<? include 'paginas/fragments/modais-anotacoes.php'; ?>
	<? include 'paginas/fragments/modal-busca-empresa.php'; ?>

</div>

<!--<script type="application/javascript" language="javascript" src="./js/jquery/jquery.mask.min.js"></script>-->
<script type="application/javascript" language="javascript" src="./js/meusestagios.js"></script>
<script type="application/javascript" language="javascript" src="./js/modal-busca-empresa.js"></script>
<script type="application/javascript" language="javascript" src="./js/editaestagio.js"></script>
