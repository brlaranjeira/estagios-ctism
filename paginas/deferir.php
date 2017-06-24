<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 29/06/16
 * Time: 17:58
 */
ini_set('default_charset','utf-8');
session_start();
require_once ("Usuario.php");
require_once ("Paginas.php");
Paginas::forcaSeguranca();

?>

<table id="tabela-deferir" class="table table-striped">
    <thead>
        <th></th>
        <th>Aluno</th>
        <th>Professor</th>
        <th>Curso</th>
        <th>Nota</th>
        <th>Situa&ccedil;&atilde;o</th>
        <th>Anota&ccedil;&otilde;es</th>
        <th>Escrever Anota&ccedil;&atilde;o</th>
    </thead>
    <tbody>

    </tbody>
</table>

<? include 'paginas/fragments/modais-anotacoes.php'; ?>

<script type="application/javascript" language="javascript" src="./js/deferir.js"></script>
