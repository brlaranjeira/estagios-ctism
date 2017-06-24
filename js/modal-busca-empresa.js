/**
 * Created by bruno on 14/12/16.
 */

function loadCidades( callback ) {
    var uf = document.getElementById('emp-uf').value;
    $.ajax('./paginas/loadcidades.php', {
        data: {'uf': uf},
        success: function ( response ) {
            var cidades = JSON.parse(response).cidades;
            $('#emp-cid').find('option').remove().end().append('<option>');
            for (var i=0;i<cidades.length;i++) {
                $('#emp-cid').append('<option value="' + cidades[i].id + '">' + cidades[i].nome + '</option>');
            }
            callback != undefined && callback();
        }, error: function ( response ) {
            showError(response.responseText);
        }, complete: function ( response ) {
        }
    });
}



function CNPJOnType( ) {
    var txt = document.getElementById('emp-cnpj').value;
    if (txt.length == 18) {
        showCarregando();
        $.ajax('./paginas/buscaempresa.php/', {
            data: {'cnpj': txt},
            success: function (response) {
                if (response.length == 0) { //sem cnpj
                    empresaRecuperada = null;
                    //testa o receitaws
                    var receitaUrl = './paginas/consultareceita.php';
                    var receitaObj = {
                        timeout: 6000,
                        data: {'cnpj': txt.replace(/[.\-\/]/g, '')},
                        success: function (response2) {
                            var empresa = JSON.parse(response2);
                            if (empresa.status != 'OK') {
                                $('[id^=emp]').not('#emp-cnpj,#emp-id').attr('disabled', true).val('');
                            } else {
                                $('[id^=emp-]').attr('disabled', false);
                                document.getElementById('emp-rsoc').value = empresa.nome.length ? empresa.nome : '';
                                document.getElementById('emp-cep').value = empresa.cep.length ? empresa.cep.replace(/[.]/g, '') : '';
                                document.getElementById('emp-bairro').value = empresa.bairro.length ? toTitleCase(empresa.bairro) : '';
                                document.getElementById('emp-lograd').value = empresa.logradouro.length ? toTitleCase(empresa.logradouro) : '';
                                document.getElementById('emp-nro').value = empresa.numero.length ? empresa.numero : '';
                                document.getElementById('emp-comp').value = empresa.complemento.length ? toTitleCase(empresa.complemento) : '';
                                document.getElementById('emp-uf').value = empresa.uf.length ? empresa.uf : '';
                                $('#emp-cep').mask($('#emp-cep').attr('mask'));
                                loadCidades(function () {
                                    $('#emp-cid > option').each(function () {
                                        if ($(this).text().toUpperCase() == empresa.municipio.toUpperCase()) {
                                            var value = $(this).val();
                                            $('#emp-cid').val(value).change();
                                        }
                                    });
                                })


                            }
                        }, error: function (response2) {
                            $('[id^=emp]').not('#emp-cnpj,#emp-id').attr('disabled', true).val('');
                        }, complete: function (response2) {
                            $('#emp-rsoc').attr('disabled', false);
                            $('#emp-cep').attr('disabled', false);
                            hideCarregando();
                        }
                    };
                    $.ajax(receitaUrl, receitaObj);


                } else {
                    empresa = JSON.parse(response);
                    empresaRecuperada = empresa;
                    var arr = {
                        bairro: 'emp-bairro',
                        cep: 'emp-cep',
                        razaoSocial: 'emp-rsoc',
                        logradouro: 'emp-lograd',
                        nro: 'emp-nro',
                        complemento: 'emp-comp'
                    };
                    for (var i = 0; i < Object.keys(arr).length; i++) {
                        var k = Object.keys(arr)[i];
                        var v = arr[k];
                        if (empresa[k].length) {
                            document.getElementById(v).value = empresa[k];
                        } else {
                            document.getElementById(v).value = '';
                        }
                        $('#' + v).attr('disabled', false);
                    }
                    document.getElementById('emp-uf').value = empresa.cidade.uf.id;
                    $("#emp-uf").attr('disabled', false);
                    loadCidades(function () {
                        document.getElementById('emp-cid').value = empresa.cidade.id;
                        $('#emp-cid').attr('disabled', false);
                        hideCarregando();
                    });
                    var cepMask = $('#emp-cep').attr('mask');
                    $('#emp-cep').mask(cepMask.replace('-', ''));//nao mexer
                    $('#emp-cep').mask(cepMask);
                }
            }, error: function (response) {
                hideCarregando();
            }
        });
    }
}

function CEPOnType () {
    var txt = document.getElementById('emp-cep').value;
    if (txt.length == 9) {
        showCarregando();
        $.ajax('http://viacep.com.br/ws/' + txt + '/json/', {
            timeout: 5000,
            success: function ( response ) {
                $('[id^=emp-]').attr('disabled',false);
                if (response.erro == true) {
                    hideCarregando();
                } else {
                    document.getElementById('emp-lograd').value = response.logradouro;
                    document.getElementById('emp-bairro').value = response.bairro;
                    document.getElementById('emp-uf').value = response.uf;
                    loadCidades( function () {
                        $('#emp-cid > option').each( function () {
                            if ($(this).text() == response.localidade) {
                                var value = $(this).val();
                                $('#emp-cid').val(value).change();
                            }
                        });
                        hideCarregando();
                    });
                }
            }, error: function ( response ) {
                $('[id^=emp-]').attr('disabled',false);
                hideCarregando();
            }
        });
    }
}

function confirmaEmpresa () {
    var idEmpresa = empresaRecuperada ? empresaRecuperada.id : null;
    var save = !empresaRecuperada || (
            empresaRecuperada.cnpj != document.getElementById('emp-cnpj').value.replace(/[\.\-\/]/g,'') ||
            empresaRecuperada.razaoSocial != document.getElementById('emp-rsoc').value ||
            empresaRecuperada.cep != document.getElementById('emp-cep').value.replace('-','') ||
            empresaRecuperada.cidade.id != document.getElementById('emp-cid').value ||
            empresaRecuperada.bairro != document.getElementById('emp-bairro').value ||
            empresaRecuperada.nro != document.getElementById('emp-nro').value ||
            empresaRecuperada.complemento != document.getElementById('emp-comp').value
        );
    if (save) {
        $.ajax('./paginas/salvaempresa.php', {
            data: {
                //$_REQUEST['cep'],$_REQUEST['nro'],$_REQUEST['bairro'],$_REQUEST['comp'],$_REQUEST['lograd']
                cnpj: document.getElementById('emp-cnpj').value.replace(/[\.\-\/]/g,''),
                rsoc: document.getElementById('emp-rsoc').value,
                cid: document.getElementById('emp-cid').value,
                cep: document.getElementById('emp-cep').value.replace('-',''),
                nro: document.getElementById('emp-nro').value,
                bairro: document.getElementById('emp-bairro').value,
                comp: document.getElementById('emp-comp').value,
                lograd: document.getElementById('emp-lograd').value
            }, success: function ( response ) {
                idEmpresa = response;
                var $inputGroup = $('.input-group-empresa[cod=' + document.getElementById('emp-id').value + ']');
                $inputGroup.find('[cod=id-empresa]').val(idEmpresa);
                $inputGroup.find('[cod=nomeempresa]').val('[' + document.getElementById('emp-cnpj').value + '] ' + document.getElementById('emp-rsoc').value);
                $('#modal-busca-empresa').on('hidden.bs.modal',function () {
                    $inputGroup.find('[cod=carga_horaria]').focus();
                });
                $('#modal-busca-empresa').modal('hide');
            }, error: function ( response ) {
                showError(response.responseText);
            }
        });
    } else {
        var $inputGroup = $('.input-group-empresa[cod=' + document.getElementById('emp-id').value + ']');
        $inputGroup.find('[cod=empresa-info]').val(JSON.stringify(empresaRecuperada));
        $inputGroup.find('[cod=nomeempresa]').val('[' + document.getElementById('emp-cnpj').value + '] ' + document.getElementById('emp-rsoc').value);
        $('#modal-busca-empresa').on('hidden.bs.modal',function () {
            $inputGroup.find('[cod=carga_horaria]').focus();
        });
        $('#modal-busca-empresa').modal('hide');
    }
}