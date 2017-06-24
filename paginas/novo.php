<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 26/09/16
 * Time: 12:22
 */
ini_set('default_charset','utf-8');
session_start();
require_once ("Usuario.php");
require_once ("Paginas.php");
require_once ("Grupos.php"); ?>

<form id="form-novo-estagio" method="post" action="novoestagio.php">
    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            <label for="alunoest">Estagi&aacute;rio</label>
            <select class="form-control" id="alunoest" name="alunoest">
				<?
				$alunos = Usuario::getAllFromGroups(array(Grupos::ALUNOS, Grupos::BOLSISTAS));
				echo '<option value="">Selecionar Aluno</option>';
				foreach ( $alunos as $aluno ) {
					echo '<option value="' . $aluno->getUid() . '">' . $aluno->getFullName() . '</option>';
				}
				?>
            </select>
        </div>
        <div class="form-group col-md-6 col-xs-12">
            <label for="idcurso">Curso</label>
            <select class="form-control" id="idcurso" name="idcurso">
				<?
				require_once ("Curso.php");
				$cursos = Curso::getAll();
				echo '<option value="">Selecionar Curso</option>';
				foreach ( $cursos as $curso ) {
					echo '<option value="' . $curso->getId() . '">' . $curso->getDescricao() . '</option>';
				}
				?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            <label for="profresp">Professor Respons&aacute;vel</label>
            <select class="form-control" id="profresp" name="profresp">
				<?
				$professores = Usuario::getAllFromGroups(Grupos::PROFESSORES);
				echo '<option value="">Selecionar Professor</option>';
				foreach ( $professores as $prof ) {
					echo '<option value="' . $prof->getUid() . '">' . $prof->getFullName() . '</option>';
				}
				?>
            </select>
        </div>
        <div class="form-group col-md-6 col-xs-12">
            <label for="obrigatorio">&nbsp;Est&aacute;gio obrigat&oacute;rio</label>
            <select class="form-control" id="obrigatorio" name="obrigatorio">
                <option value="on">Sim</option>
                <option value="off">N&atilde;o</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            <label for="dtini">Data de In&iacute;cio</label>
            <input type="text" placeholder="dd/mm/aaaa" name="dtini" id="dtini" class="form-control input-date">
        </div>
        <div class="form-group col-md-6 col-xs-12">
            <label for="dtfim">Data de Fim</label>
            <input type="text" placeholder="dd/mm/aaaa" name="dtfim" id="dtfim" class="form-control input-date">
        </div>
    </div>

    <div class="row">

    </div>
    <label for="local">Local(locais) de Est&aacute;gio</label>
    <div class="row">
        <div class="well">
            <div cod="0" class="input-group input-group-empresa">
                <input type="text" placeholder="Empresa (clique para procurar)" class="form-control input-empresa" cod="nomeempresa">
                <input type="text" placeholder="Carga hor&aacute;ria semanal. Ex.: 20" name="c_h[]" class="form-control carga_horaria" cod="carga_horaria">
                <input type="text" placeholder="Supervisor. Ex.: Fulano da Silva" name="supervisor[]" class="form-control" cod="supervisor">
                <input type="hidden" name="id-empresa[]" cod="id-empresa">
                <span class="span-remove-empresa input-group-addon glyphicon glyphicon-remove"></span>
                <span class="span-nova-empresa input-group-addon glyphicon glyphicon-plus"></span>
            </div>
        </div>
    </div>
    <button id="btn-novo-estagio" type="button" class="btn btn-success btn-block">Enviar!</button>

</form>

<? include "paginas/fragments/modal-busca-empresa.php"; ?>
<!--<script type="application/javascript" language="javascript" src="./js/jquery/jquery.mask.min.js"></script>-->
<script type="application/javascript" language="javascript" src="./js/modal-busca-empresa.js"></script>
<script type="application/javascript" language="javascript" src="./js/novo.js"></script>
