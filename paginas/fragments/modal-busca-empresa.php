<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 10/12/16
 * Time: 12:18
 */
?>

<div id="modal-busca-empresa" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Empresa <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-empresa">
                    <input type="hidden" id="emp-id" name="emp-id" value="">
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="emp-cnpj">CNPJ</label>
                            <input class="form-control cnpj" type="text" name="emp-cnpj" id="emp-cnpj" mask="00.000.000/0000-00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <label for="emp-rsoc">Raz&atilde;o Social</label>
                            <input disabled class="form-control" type="text" name="emp-rsoc" id="emp-rsoc">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label for="emp-cep">CEP</label>
                            <input disabled class="form-control cep" type="text" name="emp-cep" id="emp-cep" mask="00000-000">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="emp-uf">UF</label>
                            <select disabled class="form-control" id="emp-uf" name="emp-uf">
                                <option></option>
                                <?
                                require_once ("UF.php");
                                $estados = UF::getAll();
                                foreach ($estados as $estado) {
                                    echo '<option value="' . $estado->getId() . '">' . $estado->getNome() . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label for="emp-cid">Cidade</label>
                            <select disabled class="form-control" id="emp-cid" name="emp-cid">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="emp-bairro">Bairro</label>
                            <input disabled class="form-control" type="text" name="emp-bairro" id="emp-bairro">
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label for="emp-lograd">Logradouro</label>
                            <input disabled class="form-control" type="text" name="emp-lograd" id="emp-lograd">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="emp-nro">N&uacute;mero</label>
                            <input disabled class="form-control" type="text" name="emp-nro" id="emp-nro">
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label for="emp-comp">Complemento</label>
                            <input disabled class="form-control" type="text" name="emp-comp" id="emp-comp">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn-confirma-empresa" class="btn btn-success">Ok</button>
            </div>
        </div>
    </div>
</div>
