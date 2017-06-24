/**
 * Created by bruno on 29/06/16.
 */

$('#tabela-deferir').on('click','.btn-detalhes',function(){
    var cod = $(this).attr('cod');
    loadMainContent( {
        pg: 'editaestagio',
        id: cod
    } );
});

$('#tabela-deferir').on('click','.btn-deferir',function() {
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
                showError( response );
            }, complete: function ( response ) {
                loadTabelaDeferir();
            }
        })
    });
});

$('#tabela-deferir').on('click','.btn-retornar-para',function() {
    var para   = $(this).attr('para');
    var cod    = $(this).attr('cod');
    var estado = $(this).attr('estado');
    var $theRow = $(this).parentsUntil('tr').parent();
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
                                $theRow.slideUp();
                            }, error: function ( response2 ) {
                                showError( response + '<br/>' + response2 );
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
                        $theRow.slideUp();
                    }, error: function ( response ) {
                        showError( response );
                    }
                });
            } );
        });
});

/*$('#tabela-deferir').on('click','.btn-retornar-para',function(){
    modificar( 'Tem certeza que deseja retornar este estágio para que o professor o edite?', $(this).attr('cod'), $(this).attr('estado') );
});*/

$('#tabela-deferir').on('click','.btn-nova-anotacao',function(){

});

function loadTabelaDeferir() {
    $.ajax('./paginas/carregameusestagios.php', {
        data: { tp: 'super' },
        success: function ( response ) {
            var $tbody = $('#tabela-deferir tbody');
            $tbody.empty();
            var estagios = JSON.parse( response ).estagios;
            for (var i = 0; i < estagios.length; i++) {
                var estagio = estagios[i];
                var $tRow = $('<tr>');

                /* BOTOES DE ACAO */
                $tdBotoes = $('<td>');
                $btnGroup = $('<div class="btn-group"></div>');
                $tdBotoes.append($btnGroup);
                $btnGroup.append('<button type="button" class="btn btn-default">A&ccedil;&otilde;es</button>');
                $btnGroup.append('<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>');
                var $dropdownList = $('<ul class="dropdown-menu" role="menu">');
                $btnGroup.append($dropdownList);
                $dropdownList.append('<li><a cod="' + estagio.id + '" class="li-blue btn-detalhes">Ver Detalhes</a></li>');
                $dropdownList.append('<li><a estado="4" cod="' + estagio.id + '" class="li-green btn-deferir">Deferir</a></li>');
                //$dropdownList.append('<li><a estado="1" cod="' + estagio.id + '" class="li-red btn-reabrir">Reabrir para corre&ccedil;&otilde;es</a></li>');
                $dropdownList.append('<li><a estado="1" cod="' + estagio.id + '" class="li-yellow btn-retornar-para" para="aluno">Retornar para o aluno</a></li>');
                $dropdownList.append('<li><a estado="2" cod="' + estagio.id + '" class="li-yellow btn-retornar-para" para="professor">Retornar para o professor</a></li>');
                $dropdownList.append('<li><a estado="5" cod="' + estagio.id + '" class="li-red btn-excluir" para="professor">EXCLUIR</a></li>');
                $tRow.append($tdBotoes);

                /* ALUNO */
                var $tdAluno = $('<td>' + estagio.aluno.fullName + '</td>');
                $tRow.append($tdAluno);

                /* PROFESSOR */
                var $tdProf = $('<td>' + estagio.professor.fullName + '</td>');
                $tRow.append($tdProf);

                /* CURSO */
                var $tdCurso = $('<td>' + estagio.curso.descricao + '</td>');
                $tRow.append($tdCurso);

                /* NOTA */
                var strNota = estagio.nota.length > 0
                    ? estagio.nota
                    : '<span class="span-red">Sem Nota</span>';
                var $tdNota = $('<td>' + strNota + '</td>');
                $tRow.append($tdNota);

                /* SITUACAO */
                var $tdSituacao = $('<td>' + estagio.estado.descricao + '</td>');
                $tRow.append($tdSituacao);

                /* ANOTACOES */
                var $tdAnotacoes = $('<td>').addClass('td-btn');
                $anotacoesBtn = $('<button>');
                $anotacoesBtn
                    .addClass('btn btn-xs btn-primary btn-ver-anotacoes')
                    .attr( 'estagio' , estagio.id )
                    .attr( 'anotacoes' , JSON.stringify( estagio.anotacoes ) )
                    .html( '<span class="glyphicon glyphicon-envelope"></span>&nbsp;Abrir Anota&ccedil;&otilde;es (' + estagio.anotacoes.length + ')');
                $anotacoesBtn.click( function () {
                    var anotacoes = JSON.parse($( this ).attr('anotacoes'));
                    showMessage( getHTMLAnotacoes( anotacoes , true ) );
                } );
                $tdAnotacoes.append($anotacoesBtn);
                $tRow.append($tdAnotacoes);

                /* ESCREVER ANOTACAO */
                var $tdMensagem = $('<td>');
                $tdMensagem.append('<span class="glyphicon glyphicon-comment span-blue span-nova-anotacao-estagio">');
                $tRow.append($tdMensagem);


                $tbody.append($tRow);
            }
        }, error: function ( response ) {
        }
    })
}

loadTabelaDeferir();