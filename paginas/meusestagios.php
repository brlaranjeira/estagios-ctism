<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 24/05/2016
 * Time: 16:23
 */
ini_set('default_charset','utf-8');
require_once ("Usuario.php");
require_once ("Paginas.php");
Paginas::forcaSeguranca();
(session_status() != PHP_SESSION_ACTIVE) and session_start();
?>
<!--<button id="btn-enviar" type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#novo-estagio-modal">
	<span class="glyphicon glyphicon-plus-sign"></span>
	Novo Est&aacute;gio
</button>-->
<table id="table-estagios-aluno" class="table table-striped table-hover">
	<thead>
		<th></th>
		<th>Curso</th>
		<th>Professor</th>
		<!-- <th>Per&iacute;odo</th> -->
		<!-- <th>Editar</th> -->
		<th>Situa&ccedil;&atilde;o</th>
		<th>Anota&ccedil;&otilde;es</th>
		<!--<th>Certificado</th>-->
		<!-- <th>A&ccedil;&atilde;o</th> -->
	</thead>
	<tbody>
		<?
		
		?>
	</tbody>
</table>
<!--
<div id="novo-estagio-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Novo Est&aacute;gio</h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				<button id="btn-novo-estagio" type="button" class="btn btn-success" data-dismiss="modal">Confirmar</button>
			</div>
		</div>
	</div>
</div>
-->

<!--<script type="application/javascript" language="javascript" src="./js/jquery/jquery.mask.min.js"></script>-->
<script type="application/javascript" language="javascript" src="./js/meusestagios.js"></script>