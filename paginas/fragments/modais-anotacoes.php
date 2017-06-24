<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 04/08/16
 * Time: 14:24
 */
?>

<div id="modal-escreve-anotacao-estagio" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Mensagem <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-xs-offset-0">
                        <div class="form-group">
                            <label for="mensagem-estagio">Escreva a anota&ccedil;&atilde;o referente a este est&aacute;gio.</label>
                            <textarea class="form-control" name="mensagem-estagio" id="mensagem-estagio"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-mensagem-estagio" type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-escreve-anotacao-arquivo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Mensagem
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-xs-offset-0">
                        <div class="form-group">
                            <label for="mensagem-arquivo">Escreva a mensagem aqui.</label>
                            <textarea class="form-control" name="mensagem-arquivo" id="mensagem-arquivo"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-mensagem-arquivo" type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>