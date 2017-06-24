<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/06/16
 * Time: 14:00
 */
ini_set('default_charset','utf-8');
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
require_once ("Paginas.php");
require_once ("Usuario.php");
require_once ("Grupos.php");
require_once ("Estagio.php");
Paginas::forcaSeguranca();

$usuario = Usuario::unserialize($_SESSION['ctism_user']);
$estagios = $usuario->hasGroup(Grupos::PROFESSORES) ? Estagio::getAllFromProfessor($usuario) : array();

?>
<table id="tabela-estagios-prof" class="table table-striped">
    <thead>
        <th></th>
        <th>Curso</th>
        <th>Aluno</th>
        <th>Situa&ccedil;&atilde;o</th>
        <th>Anota&ccedil;&otilde;es</th>
        <th>Nova Anota&ccedil;&atilde;o</th>
    </thead>
    <tbody>

    </tbody>
</table>


<? include 'paginas/fragments/modais-anotacoes.php'; ?>

<script type="application/javascript" language="javascript" src="./js/avalpendentes.js"></script>