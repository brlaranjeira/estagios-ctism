<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 05/08/16
 * Time: 17:01
 */
ini_set('default_charset','utf-8');
?>
<form id="form-filtro">
    <div id="div-filtro">
        <div class="row row-filter">
            <div class="col-xs-12 col-md-4 form-group">
                <select class="form-control" cod="filtro_tipo" name="filtro_tipo[]">
                    <option></option>
                    <option value="aluno" tp="select" opt="=">Aluno</option>
                    <option value="professor" tp="select" opt="=">Professor</option>
                    <option value="data_inicio" tp="input" mask="00/00/0000">Data de In&iacute;cio</option>
                    <option value="data_fim" tp="input" mask="00/00/0000">Data de T&eacute;rmino</option>
                    <option value="id_curso" tp="select" opt="=">Curso</option>
                    <option value="id_estado" tp="select">Situa&ccedil;&atilde;o</option>
                    <option value="nota" tp="input" mask="00,00">Nota</option>
                    <option value="empresa_cnpj" tp="input" mask="00.000.000/0000-00" opt="=">CNPJ da Empresa</option>
                    <option value="empresa_razao_social" tp="input" opt="like">Raz&atilde;o Social da Empresa</option>
                </select>
            </div>
            <div class="col-xs-12 col-md-4 form-group">
                <select class="form-control" cod="filtro_operador" name="filtro_operador[]">
                    <option value="=">Igual a</option>
                    <option value=">">Maior que</option>
                    <option value="<">Menor que</option>
                    <option value=">=">Maior ou igual a</option>
                    <option value="<=">Menor ou igual a</option>
                    <option value="like">Contém</option>
                </select>
            </div>
            <div class="col-xs-12 col-md-4 form-group">
                <div class="input-group">
                    <input class="form-control" type="text" cod="filtro_param" name="filtro_param[]">
                    <select class="form-control hidden" cod="filtro_param"></select>
                    <span class="input-group-addon glyphicon glyphicon-remove btn-rem-filter"></span>
                    <span class="input-group-addon glyphicon glyphicon-plus btn-add-filter"></span>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-xs-12 form-group">
        <button id="btn-filtrar" class="btn btn-block btn-primary">Filtrar&nbsp;<span class="glyphicon glyphicon-search"></span></button>
    </div>
    <div class="col-xs-12 form-group">
        <button id="btn-relatorio" class="btn btn-block btn-success">Imprimir relat&oacute;rio&nbsp;<span class="glyphicon glyphicon-list-alt"></span></button>
    </div>
</div>
<div class="row">
    <table id="tabela-todos" class="table table-striped">
        <thead>
            <th></th>
            <th cod="aluno">Aluno</th>
            <th cod="professor">Professor</th>
            <th cod="id_curso">Curso</th>
            <th cod="data_inicio">Data Inicial</th>
            <th cod="data_fim">Data Final</th>
            <th cod="id_estado">Situa&ccedil;&atilde;o</th>
            <th cod="empresas">Empresa(s)</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<? include 'paginas/fragments/modais-anotacoes.php'; ?>

<script type="application/javascript" language="javascript" src="./js/todos.js"></script>