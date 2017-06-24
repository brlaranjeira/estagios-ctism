<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 01/07/16
 * Time: 15:25
 */
?>

<form id="form-envia-arquivo" enctype="multipart/form-data" method="post" action="enviaarquivo.php">
    <input type="hidden" name="idestagio" id="idestagio" value="<?=$estagio->getId()?>">
    <legend>Novo Arquivo</legend>
        <div class="row">
            <div class="form-group col-md-6 col-xs-12">
                <label for="descrarq">Descri&ccedil;&atilde;o</label>
                <input type="text" class="form-control" name="descrarq" id="descrarq">
            </div>
            <div class="form-group col-md-6 col-xs-12">
                <label for="arq">Arquivo</label>
                <input type="file" class="form-control" name="arq" id="arq">
            </div>
        </div>
        <? if ( $usuario->getUid() != $estagio->getAluno() && ($usuario->getUid() == $estagio->getProfessor() || $usuario->hasGroup(Grupos::DREC_SECRETARIA) || $usuario->hasGroup(Grupos::SSI) ) ) { ?>
            <div class="row">
                <div class="form-group col-xs-12">
                    <label><input type="checkbox" name="arq_inv">&nbsp;Arquivo vis&iacute;vel apenas para o professor e o DREC</label>
                </div>
            </div>
        <? } ?>
    <div class="row">
    <div class="col-xs-12">
    <button id="btn-enviar-arquivo" type="button" class="btn btn-success btn-block">
    Enviar
    </button>
    </div>
    </div>
</form>
<table id="table-arquivos-estagio" class="table table-striped table-hover">
    <thead>
    <th width="80%">Arquivo</th>
    <th>Ver Anota&ccedil;&otilde;es</th>
    <th>Baixar</th>
    <? if ($usuario->getUid() == $estagio->getAluno()) { echo '<th>Excluir</th>'; } ?>
    <th>Nova Anota&ccedil;&atilde;o</th>
    </thead>
    <tbody id="tbody-arquivos-estagio">

    </tbody>
</table>
