/**
 * Created by bruno on 12/08/16.
 */


$('#tabela-todos').on('click','.btn-detalhes',function(){
    var cod = $(this).attr('cod');
    loadMainContent( {
        pg: 'editaestagio',
        id: cod
    } );
});

/*
$('#tabela-todos').on('click','.btn-deferir',function() {
    var cod = $(this).attr('cod');
    var estado = $(this).attr('estado');
    confirmModal( 'Tem certeza que deseja <strong>DEFERIR</strong> este estágio' , function () {
        $.ajax( './paginas/alteradados.php' , {
            data: {
                cod: cod,
                estado: estado
            }, success: function( response ) {
                showMessage( response );
            }, error: function ( response ) {
                showError( response.responseText );
            }, complete: function ( response ) {
                loadTabelaTodos( 'aluno','asc' );
            }
        })
    });
});
*/

$('#tabela-todos').on('click','.btn-retornar-para',function() {
    var para   = $(this).attr('para');
    var cod    = $(this).attr('cod');
    var estado = $(this).attr('estado');
    confirmModal('Deseja escrever uma mensagem ao ' + para + '?',
        function() { // clicou sim
            $('#btn-mensagem-estagio').unbind('click');
            $('#btn-mensagem-estagio').click( function () {
                var msg = document.getElementById('mensagem-estagio').value;
                $.ajax('./paginas/criaanotacao.php', {
                    data: {
                        msg: msg,
                        estagio: cod
                    }, success: function( response ) {
                        $.ajax('./paginas/alteradados.php', {
                            data: {
                                cod: cod,
                                estado: estado
                            }, success: function ( response2 ) {
                                showMessage( response + '<br/>' + response2 );
                            }, error: function ( response2 ) {
                                showError( response.responseText + '<br/>' + response2.responseText );
                            }, complete: function ( response ) {
                                loadTabelaTodos( 'aluno','asc' );
                            }
                        } );
                    }, error: function () {
                        showError( response );
                    }
                });
            });
            $('#modal-escreve-anotacao-estagio').modal('show');
        }, function () {//clicou nao
            confirmModal( 'Tem certeza que deseja reabrir o estágio para correções sem escrever nenhuma mensagem ao ' + para + '?', function () {
                $.ajax('./paginas/alteradados.php', {
                    data: {
                        cod: cod,
                        estado: estado
                    }, success: function ( response ) {
                        showMessage( response );
                    }, error: function ( response ) {
                        showError( response.responseText );
                    }, complete: function ( response ) {
                        loadTabelaTodos( 'aluno','asc' );
                    }
                });
            } );
        });
});

$('#tabela-todos').on('click','.btn-excrest',function () {
    var cod = $(this).attr('cod');
    var acao = $(this).attr('acao');
    confirmModal('Tem certeza que deseja ' + $(this).text().toUpperCase() + ' este estágio?', function () {
        $.ajax('./paginas/excluirestaura.php', {
            data: {
                cod: cod,
                acao: acao
            }, type: 'post',
            success: function ( response ) {
                showMessage( response );
            }, error: function ( response ) {
                showError( message );
            }, complete: function ( response ) {
                loadTabelaTodos( 'aluno','asc' );
            }
        });
    });



});

function loadTabelaTodos( criterion , sort, filters ) {
    if (filters == undefined) {
        filters = {};
    }
    var $tbody = $('#tabela-todos tbody');
    $.ajax('./paginas/carregameusestagios.php', {
        data: {
            tp: 'all',
            criterion: criterion,
            sort: sort,
            filters: filters
        }, success: function ( response ) {
            $tbody.empty();
            var estagios = JSON.parse( response ).estagios;
            for (var i = 0; i < estagios.length; i++) {
                var estagio = estagios[i];
                $tr = $('<tr>');
                /* ACOES */
                var $tdBotoes = $('<td>');
                $btnGroup = $('<div class="btn-group"></div>');
                $tdBotoes.append($btnGroup);
                $btnGroup.append('<button type="button" class="btn btn-default">A&ccedil;&otilde;es</button>');
                $btnGroup.append('<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>');
                var $dropdownList = $('<ul class="dropdown-menu" role="menu">');
                $btnGroup.append($dropdownList);
                $dropdownList.append('<li><a cod="' + estagio.id + '" class="li-blue btn-detalhes">Ver Detalhes</a></li>');
                if ( estagio.estado.id != 5 ) {
                    //estagio.estado.id == 3 && $dropdownList.append('<li><a estado="4" cod="' + estagio.id + '" class="li-green btn-deferir">Deferir</a></li>');
                    estagio.estado.id != 1 && $dropdownList.append('<li><a estado="1" cod="' + estagio.id + '" class="li-yellow btn-retornar-para" para="aluno">Retornar para o aluno</a></li>');
                    estagio.estado.id > 2 && $dropdownList.append('<li><a estado="2" cod="' + estagio.id + '" class="li-yellow btn-retornar-para" para="professor">Retornar para o professor</a></li>');
                    $dropdownList.append('<li><a estado="5" cod="' + estagio.id + '" acao="e" class="li-red btn-excrest" >EXCLUIR</a></li>');
                } else {
                    $dropdownList.append('<li><a cod="' + estagio.id + '" acao="r" class="li-green btn-excrest">Restaurar</a></li>')
                }

                $tr.append($tdBotoes);

                /* ALUNO */
                var $tdAluno = $('<td>').html(estagio.aluno.fullName);
                $tr.append($tdAluno);

                /* PROFESSOR */
                var $tdProf = $('<td>').html(estagio.professor.fullName);
                $tr.append($tdProf);

                /* CURSO */
                var $tdCurso = $('<td>').html(estagio.curso.descricao);
                $tr.append($tdCurso);

                /* DATA INICIAL */
                var $tdDtIni = $('<td>').html(estagio.dt_inicio);
                $tr.append($tdDtIni);

                /* DATA FINAL */
                var $tdDtFim = $('<td>').html(estagio.dt_fim);
                $tr.append($tdDtFim);

                /* SITUACAO */
                var strSituacao = estagio.estado.descricao;

                if ( estagio.nota.length ) {
                    strSituacao += ' - Nota: <strong>' + estagio.nota + '</strong>';
                }
                var $tdSituacao = $('<td>').html(strSituacao);
                $tr.append($tdSituacao);
                estagio.estado.id == 5 && $tr.addClass('danger');

                /* EMPRESAS */
                var $tdEmpresas = $('<td>').html('<td><span cod="' + estagio.id + '" class="detalhes-empresa glyphicon glyphicon-info-sign"></span></td>');
                //$tdEmpresas.find('.tooltip-empresa').tooltip();
                /*$.ajax('./paginas/carregaempresas.php', {
                    data: { estagio: estagio.id },
                    success: function ( response ) {
                        var empresas = JSON.parse( response ).empresas;
                        var str = '';
                        for (var i = 0; i < empresas.length; i++) {
                            var empresa = empresas[i];
                            str += '<h5>' + empresa.razaoSocial + ' [' + empresa.cnpj + ']</h5>';
                            str += '<h6>' + empresa.cidade.nome + ' - ' + empresa.cidade.uf.id + '</h6>';
                        }
                        $tdEmpresas.find('.tooltip-empresa').attr('data-original-title',str);
                    }, error: function ( response ) {
                        tdEmpresas.find('.tooltip-empresa').attr('data-original-title',response.responseText);
                    }
                });*/
                $tr.append($tdEmpresas);

                $tbody.append($tr);

            }
        }, error: function ( response ) {

        }
    })
}
loadTabelaTodos('aluno','asc');

$('#tabela-todos tbody').on('click','.btn-detalhes', function () {
    loadMainContent({pg:'editaestagio',id:$(this).attr('cod')});
});

$('#tabela-todos tbody').on('click','span.detalhes-empresa',function () {
    var cod = $(this).attr('cod');
    $.ajax('./paginas/carregaempresas.php', {
        data: { estagio: cod },
        success: function ( response ) {
            empresas = JSON.parse(response).empresas;
            txt = '';
            for (var i = 0; i < empresas.length; i++) {
                if (i > 0) {
                    //txt += '<span class="span-title-empresa"></span>';
                }
                var empresa = empresas[i];
                var cnpj = empresa.cnpj;
                var maskedCnpj = cnpj.substr(0,2) + '.' + cnpj.substr(2,3) + '.' + cnpj.substr(5,3) + '/' + cnpj.substr(8,4) + '-' + cnpj.substr(12,2);
                txt += '<span class="span-title-empresa">CNPJ: '+maskedCnpj+'</span>';
                txt += '<span class="span-info-empresa-key">Raz&atilde;o Social</span>';
                txt += '<span class="span-info-empresa-val">' + empresa.razaoSocial + '</span>';
                txt += '<span class="span-info-empresa-key">Supervisor</span>';
                txt += '<span class="span-info-empresa-val">' + empresa.supervisor + '</span>';
                txt += '<span class="span-info-empresa-key">Carga Hor&aacute;ria</span>';
                txt += '<span class="span-info-empresa-val">' + empresa.carga_horaria + ' horas semanais</span>';
                txt += '<span class="span-info-empresa-key">Endere&ccedil;o</span>';
                txt += '<span class="span-info-empresa-val">' + empresa.logradouro + ', n&uacute;mero ' + empresa.nro + ', ' + empresa.complemento  + '</span><br/>';
                txt += '<span class="span-info-empresa-val">Bairro ' + empresa.bairro + ', CEP ' + empresa.cep.substr(0,5) + '-' + empresa.cnpj.substr(5,3) + '</span><br/>';
                txt += '<span class="span-info-empresa-val">' + empresa.cidade.nome + ' - ' + empresa.cidade.uf.id + '</span>';
            }
            showMessage(txt);
        }, error: function ( response ) {
        }
    })

});

$('#div-filtro').on('change','select[cod=filtro_tipo]', function () {


    var ftipo = $(this).val();
    var tp = $('option:selected', this).attr('tp');
    var mask = $('option:selected', this).attr('mask');
    var opt = $('option:selected', this).attr('opt');

    var $theRow = $(this).parentsUntil('.row').last().parent();
    $theRow.find('[cod=filtro_param]').not(tp).removeAttr('name').addClass('hidden');
    $theRow.find(tp + '[cod=filtro_param]').attr('name','filtro_param[]').removeClass('hidden');

    if (opt != undefined) {
        $theRow.find('[cod=filtro_operador]').val(opt).attr('disabled',true);
    } else {
        $theRow.find('[cod=filtro_operador]').attr('disabled',false);
    }

    if (tp == 'select') {
        showCarregando();
        $.ajax('./paginas/loadinfo.php', {
            data: { tp: ftipo },
            success: function ( response ) {
                hideCarregando();
                var $select = $theRow.find('select[cod=filtro_param]');

                $select.empty();
                var dados = JSON.parse( response ).todos;
                for (var i=0; i < dados.length; i ++) {
                    var $opt = $('<option value="' + dados[i].val + '">' + dados[i].text + '</option>');
                    $select.append($opt);
                }

            }, error: function ( response ) {
                hideCarregando();
                showError( response.responseText );
            }, complete: function () {

            }
        });
    } else {

        if (mask != undefined) {
            $theRow.find('input[cod=filtro_param]').mask(mask);
        } else {
            $theRow.find('input[cod=filtro_param]').unmask();
        }
    }

});

$('#div-filtro').on('click','.btn-add-filter',function () {
    $cpy = $(this).parentsUntil('.row-filter').last().parent().clone();
    $('.row-filter').find('[cod=filtro_tipo] :selected[tp=select]').each(function () {
        $cpy.find('[cod=filtro_tipo] option[value='+$(this).val()+']').remove();
    });
    $('#div-filtro').append($cpy);
});

$('#div-filtro').on('click','.btn-rem-filter',function () {
    $row = $(this).parentsUntil('.row-filter').last().parent();
    if ($('.row-filter').length  > 1) {
        $row.remove();
    } else {
        $row.find('[cod=filtro_tipo],[cod=filtro_param]').val('');
    }
});

$('#btn-filtrar').click(function () {
    var $disabled = $('#form-filtro :disabled');
    $disabled.attr('disabled',false);
    var x = $('#form-filtro').serializeArray();
    $disabled.attr('disabled',true);
    loadTabelaTodos( 'aluno', 'asc', x);
});