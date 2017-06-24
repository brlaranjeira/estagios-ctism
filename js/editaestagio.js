/**
 * Created by SSI-Bruno on 25/05/2016.
 */

$('input.input-date').mask('00/00/0000');

$('#form-edita-estagio').on('focus', '.input-empresa', function() {
    document.getElementById('emp-id').value=$(this).parent().attr('cod');
    var info = JSON.parse($(this).parent().find('[cod="empresa-info"]').val());
    empresaRecuperada = info;
    $('#modal-busca-empresa').find('input[type=text],select').attr('disabled',false);

    $('#modal-busca-empresa').modal('show');

    document.getElementById('emp-cnpj').value = info.cnpj.length ? info.cnpj : '';
    document.getElementById('emp-rsoc').value = info.razaoSocial.length ? info.razaoSocial : '';
    document.getElementById('emp-cep').value = info.cep.length ? info.cep : '';
    document.getElementById('emp-bairro').value = info.bairro.length ? info.bairro : '';
    document.getElementById('emp-lograd').value = info.logradouro.length ? info.logradouro : '';
    document.getElementById('emp-nro').value = info.nro.length ? info.nro : '';
    document.getElementById('emp-comp').value = info.complemento.length ? info.complemento : '';
    document.getElementById('emp-uf').value = info.cidade.uf.id;
    var cnpjMask = $('#emp-cnpj').attr('mask');
    var cepMask = $('#emp-cep').attr('mask');
    $('#emp-cnpj').mask(cnpjMask.replace(/[\.\-\/]/g,''));
    $('#emp-cnpj').mask(cnpjMask);
    $('#emp-cnpj').on( 'input', CNPJOnType );
    $('#emp-cep').mask(cepMask.replace('-',''));
    $('#emp-cep').mask(cepMask);
    $('#emp-cep').on( 'input', CEPOnType )
    showCarregando();
    loadCidades( function () {
        document.getElementById('emp-cid').value = info.cidade.id;
        hideCarregando();
    });
});


function iniciaEdicao () {
    var cod = $(this).attr('cod');
    if ($(this).hasClass('span-edita-empresa')) {
        $(this).parent().find('input[type="text"]').attr('disabled',false);
    } else {
        $('#form-edita-estagio').find('#' + cod ).attr('disabled',false);
    }
    $(this).removeClass('glyphicon-edit span-editar').addClass('glyphicon-ok span-editar-ok');
    $('#btn-edita-estagio').attr('disabled',true);
    $('#btn-edita-estagio').removeClass('btn-success').addClass('btn-default');
    $(this).unbind('click');
    $(this).on( 'click', finalizaEdicao );
}

function finalizaEdicao () {
    var cod = $(this).attr('cod');
    if ($(this).hasClass('span-edita-empresa')) {
        $(this).parent().find('input[type="text"]').attr('disabled',true);
    } else {
        $('#form-edita-estagio').find('#' + cod ).attr('disabled',true);
    }
    $(this).removeClass('glyphicon-ok span-editar-ok').addClass('glyphicon-edit span-editar');
    if ( $('.span-editar-ok').length == 0 ) {
        $('#btn-edita-estagio').attr('disabled',false);
        $('#btn-edita-estagio').removeClass('btn-default').addClass('btn-success');
    }
    $(this).unbind('click');
    $(this).on( 'click', iniciaEdicao );
}

$('#btn-edita-estagio').click( function () {
    confirmModal("Deseja alterar as informações deste estágio?",
        function () {
            //input-group-empresa > { cod=empresa-info,cod=carga_horaria,cod=supervisor }
            var empresas = Array();
            $('.input-group-empresa').each( function() {
                //input-group-empresa > { cod=empresa-info,cod=carga_horaria,cod=supervisor }
                var $elm = $(this);
                var currEmpresa = {};
                currEmpresa.id = JSON.parse($elm.find('[cod=empresa-info]').val()).id;
                currEmpresa.carga_horaria = $elm.find('[cod=carga_horaria]').val();
                currEmpresa.supervisor = $elm.find('[cod=supervisor]').val();
                empresas.push(currEmpresa);
            });
            var cod = getAllParams().id;
            $.ajax('./paginas/alteradados.php', {
                method: 'post',
                data: {
                    cod: cod,
                    idaluno: document.getElementById('idaluno').value,
                    profresp: document.getElementById('profresp').value,
                    dtini: document.getElementById('dtini').value,
                    dtfim: document.getElementById('dtfim').value,
                    idcurso: document.getElementById('idcurso').value,
                    obrigatorio: document.getElementById('obrigatorio').value,
                    nota: document.getElementById('nota').value,
                    empresas: empresas
                }, success: function (response) {
                    showMessage(response);
                    var $botaoRapido = $('#div-btn-finalizar > .btn-alterar[estado=3]');
                    if ($botaoRapido.length) {
                        $botaoRapido.attr( 'disabled' , document.getElementById('nota').value == '' );
                    }
                }, error: function (response) {
                    showError(response);
                }
            });
        });
});

$('.span-editar').on( 'click', iniciaEdicao );

$('#btn-enviar-arquivo').on('click', function () {
    $('#form-envia-arquivo').submit();
});


$('#form-envia-arquivo').submit( function ( evt ) {
    var url = './paginas/' + $('#form-envia-arquivo').attr('action');
    //var formdata = $('#form-envia-arquivo').serializeArray();
    $.ajax (url, {
        data: new FormData(this),
        processData: false,
        contentType: false,
        type: $(this).attr('method'),
        success: function ( response ) {
            showMessage( response );
            loadTabelaArquivos();
        }, error: function ( response ) {
            showError( response.responseText );
        } /* flores em vida */
    });
    evt.preventDefault();
});

$('#table-arquivos-estagio').on('click', '.span-deletar-arquivo', function () {
    var cod = $(this).attr('cod');
    var $theRow = $(this).parentsUntil('tr').parent();
    confirmModal('Tem certeza que deseja <strong>EXCLUIR</strong> este arquivo?', function () {
        $.ajax('./paginas/deletaarquivo.php', {
            method: 'post',
            data: { codarq: cod },
            success: function ( response ) {
                showMessage( response );
                $theRow.slideUp();
            }, error: function ( response ) {
                showError( response );
                loadTabelaArquivos();
            }
        });
    });
});

function modalCriaAnotacaoEstagio ( type, id ) {
    $('#btn-mensagem-arquivo').unbind('click');
    var data = {};
    data[type] = id;
    $('#btn-mensagem-' + type ).click( function() {
        data.msg = document.getElementById('mensagem-' + type).value;
        $.ajax('./paginas/criaanotacao.php', {
            data: data,
            success: function ( response ) {
                showMessage( response );
            }, error: function ( response ) {
                showError( response );
            }, complete: function () {
                loadTabelaArquivos();
                loadTelaAnotacoes();
            }
        });
    } );
    $('#mensagem-'+type).val('');
    $('#modal-escreve-anotacao-' + type).modal('show');
}

$('#table-arquivos-estagio').on('click','.span-nova-anotacao-arquivo', function() {
    var cod = $(this).attr('cod');
    modalCriaAnotacaoEstagio( 'arquivo', cod );
});

$('#btn-nova-anotacao-estagio').on('click',function() {
    var cod = getParam('id');
    modalCriaAnotacaoEstagio( 'estagio', cod );
});

function loadTelaAnotacoes() {
    var xyz = { estagio: getParam('id')};
    $.ajax( './paginas/carregaanotacoes.php' , {
        type: 'get',
        data: xyz,
        success: function ( response ) {
            $('#div-anotacoes').html(getHTMLAnotacoes(JSON.parse(response).anotacoes, true));
        }, error: function ( response ) {
        }
    });
}
loadTelaAnotacoes();

function loadTabelaArquivos () {
    $.ajax( './paginas/loadarquivos.php' , {
        type: 'get',
        data: {
            idestagio: $('#idestagio').val()
        }, success: function ( response ) {
            $('#tbody-arquivos-estagio').html( response );
        }, error: function ( response ) {
            $('#tbody-arquivos-estagio').html( response );
        }
    });
}
loadTabelaArquivos();

$('#btn-confirma-empresa').click( confirmaEmpresa );


