/**
 * Created by root on 22/06/16.
 */
$('#tabela-estagios-prof').on('click','.btn-reabrir',function () {
    var cod = $(this).attr('cod');
    var estado = $(this).attr('estado');
    var $theRow = $(this).parentsUntil('tr').parent();
    
    confirmModal(
        'Deseja escrever uma mensagem ao aluno?',
        function () { //clicou sim
            $('#btn-mensagem-estagio').unbind('click');
            $('#btn-mensagem-estagio').click( function () {
                var msg = document.getElementById('mensagem-estagio').value;
                $.ajax('./paginas/criaanotacao.php', {
                    data: {
                        msg: msg,
                        estagio: cod
                    }, success: function ( response ) {
                        $.ajax('./paginas/alteradados.php', {
                            data: {
                                cod: cod,
                                estado: estado
                            }, success: function ( response2 ) {
                                showMessage( response + '<br/>' + response2 );
                                $theRow.slideUp();
                            }, error: function ( response2 ) {
                                showError( response + '<br/>' + response2 );
                            }
                        });
                    }, error: function ( response ) {
                        showError( response );
                    }
                });
            } );
            $('#modal-escreve-anotacao-estagio').modal('show');
        }, function() { //clicou nao
            confirmModal('Tem certeza que deseja reabrir o estágio para correções sem escrever nenhuma mensagem ao aluno?', function() {
                $.ajax('./paginas/alteradados.php', {
                    data: {
                        cod: cod,
                        estado: estado
                    }, success: function ( response ) {
                        showMessage( response );
                        loadTabelaEstagiosProf();
                    }, error: function ( response ) {
                        showError( response );
                        loadTabelaEstagiosProf();
                    }
                });
            });

        });
});

$('#tabela-estagios-prof').on('click','.btn-reabrir-aval',function () {
    var cod = $(this).attr('cod');
    var estado = $(this).attr('estado');
    var $theRow = $(this).parentsUntil('tr').parent();
    confirmModal('Tem certeza que deseja reabrir a avaliação deste estágio?', function () {
        $.ajax('./paginas/alteradados.php', {
            data: {
                cod: cod,
                estado: estado
            }, success: function ( response ) {
                showMessage( response );
                loadTabelaEstagiosProf();
            }, error: function ( response ) {
                showError( response );
            }
        });
    });
});

$('#tabela-estagios-prof').on('click','.btn-detalhes',function () {
    var cod = $(this).attr('cod');
    loadMainContent( {
        pg: 'editaestagio',
        id: cod
    } );
});

$('#tabela-estagios-prof').on('click','.btn-concluir', function () {
    var cod = $(this).attr('cod');
    var estado = $(this).attr('estado');
    var $theRow = $(this).parentsUntil('tr').parent();
    confirmModal('Tm certeza que deseja concluir a avaliação deste estágio?', function() {
        $.ajax('./paginas/alteradados.php', {
            data: {
                cod: cod,
                estado: estado
            }, success: function ( response ) {
                showMessage( response );
                loadTabelaEstagiosProf();
            }, error: function ( response ) {
                showError( response );
                loadTabelaEstagiosProf();
            }
        });
    });
});

$('#tabela-estagios-prof').on('click','.span-nova-anotacao-estagio', function() {
    var cod = $(this).attr('cod');
    $('#btn-mensagem-estagio').unbind('click');
    $('#btn-mensagem-estagio').click( function() {
        var msg = document.getElementById('mensagem-estagio').value;
        $.ajax('./paginas/criaanotacao.php', {
            data: {
                msg: msg,
                estagio: cod
            }, success: function ( response ) {
                showMessage( response );
                loadTabelaArquivos();
            }, error: function ( response ) {
                showError( response );
                loadTabelaArquivos();
            }
        });
    });
    document.getElementById('mensagem-estagio').value = '';
    $('#mensagem-estagio').val('');
    $('#modal-escreve-anotacao-estagio').modal('show');
});

function loadTabelaEstagiosProf() {
    $.ajax('./paginas/carregameusestagios.php', {
        data: {tp: 'professor'},
        method: 'get',
        success: function ( response ) {
            var estagios = JSON.parse( response ).estagios;
            var $tbody = $('#tabela-estagios-prof tbody');
            $tbody.empty();
            for (var i = 0; i < estagios.length; i++) {
                var estagio = estagios[i];
                var $theRow = $('<tr/>');

                /* BOTOES DE ACAO */
                var $btnGroup = $('<div class="btn-group"></div>');
                $theRow.append($btnGroup);
                    $btnGroup.append('<button type="button" class="btn btn-default">A&ccedil;&otilde;es</button>');
                    $btnGroup.append('<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>');
                    var $dropdownList = $('<ul class="dropdown-menu" role="menu">');
                    $btnGroup.append($dropdownList);
                        $dropdownList.append('<li><a cod="' + estagio.id + '" class="li-blue btn-detalhes">Ver e Alterar Detalhes</a></li>');
                        if ( estagio.estado.id == 3 ) {
                            $dropdownList.append('<li><a estado="2" cod="' + estagio.id + '" class="li-red btn-reabrir-aval">Reabrir Avalia&ccedil;&atilde;o</a></li>');
                        }
                        if ( estagio.estado.id == 2 ) {
                            $dropdownList.append('<li><a estado="1" cod="' + estagio.id + '" class="li-red btn-reabrir">Retornar para o aluno</a></li>');
                            if ( estagio.nota.length > 0 ) {
                                $dropdownList.append(' <li><a estado="3" cod="' + estagio.id + '" class="li-green btn-concluir">Concluir avalia&ccedil;&atilde;o</a></li>');
                            } else {
                                $dropdownList.append(' <li><a disabled estado="3" cod="' + estagio.id + '" class="li-gray">Nota Pendente</a></li>');
                            }
                        }

                /* CURSO */
                $theRow.append('<td>' + estagio.curso.descricao + '</td>');

                /* ALUNO */
                $theRow.append('<td>' + estagio.aluno.fullName + '</td>');


                /* SITUACAO */
                var strEstado = estagio.estado.descricao;
                if (estagio.estado.id > 1 && estagio.estado.id < 5 && estagio.nota.length > 0 ) {
                    strEstado += ' (Nota:<strong>'+estagio.nota+'</strong>)';
                }
                $theRow.append('<td>' + strEstado + '</td>');


                var $tdAnotacoes = $('<td>');
                $tdAnotacoes.addClass('td-btn');
                $anotacoesBtn = $('<button>');
                $anotacoesBtn
                    .addClass('btn btn-xs btn-primary btn-ver-anotacoes')
                    .attr( 'estagio' , estagio.id )
                    .attr( 'anotacoes' , JSON.stringify( estagio.anotacoes ) )
                    .html( '<span class="glyphicon glyphicon-envelope"></span>&nbsp;Abrir Anota&ccedil;&otilde;es (' + estagio.anotacoes.length + ')');
                $anotacoesBtn.click( function () {
                    var anotacoes = JSON.parse($( this ).attr('anotacoes'));
                    showMessage( getHTMLAnotacoes( anotacoes , true ) )
                } );
                $tdAnotacoes.append( $anotacoesBtn );
                $theRow.append( $tdAnotacoes );


                /* NOVA ANOTACAO */
                var $tdNovaAnotacao = $('<td>');
                $tdNovaAnotacao.addClass('td-btn');
                var $spanNovaAnotacao = $('<span>')
                    .attr('cod',estagio.id)
                    .addClass('glyphicon glyphicon-comment span-nova-anotacao-estagio span-blue');
                $tdNovaAnotacao.append($spanNovaAnotacao);
                $theRow.append($tdNovaAnotacao);
                
                $tbody.append($theRow);
            }
        }, error: function ( response ) {
            showError( response );
        }
    });
}

loadTabelaEstagiosProf();