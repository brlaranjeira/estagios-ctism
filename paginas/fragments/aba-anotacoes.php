<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 01/07/16
 * Time: 15:35
 */
require_once ("Anotacao.php");
require_once ("Utils.php");
$anotacoes = Anotacao::getAllForEstagio($estagio->getId(), $usuario->getUid() == $estagio->getAluno());
?> <button type="button" id="btn-nova-anotacao-estagio" class="btn btn-primary"> Nova Anota&ccedil;&atilde;o <span class="glyphicon glyphicon-comment"></span></button> <?
?> <div id="div-anotacoes"></div><?