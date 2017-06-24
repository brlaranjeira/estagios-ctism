/**
 * Created by SSI-Bruno on 24/05/2016.
 */




$('input[type=date]').mask('00/00/0000');
$('#btn-novo-estagio').click( function() {
    var formdata = $('#form-novo-estagio').serializeArray();
    $.ajax( './paginas/' + $('#form-novo-estagio').attr('action'), {
        data: formdata,
        method: $('#form-novo-estagio').attr('method'),
        success: function ( response ) {
            showMessage( response );
            loadTabelaMeusEstagios();
        }, error: function ( response ) {
        }
    } )
});
$('#table-estagios-aluno').on('click','.btn-editar', function () {
    var estagioId = $(this).attr('cod');
    loadMainContent( {
        pg: 'editaestagio',
        id: estagioId
    } );
});

function btnAlterarEstado () {
    var cod = $(this).attr('cod');
    var tostate = $(this).attr('estado');
    var textoModal = '';
    var $parentDiv = $(this).parent();
    switch ( tostate ) {
        case '1':
            textoModal = 'Deseja realmente reabrir este estágio para edição?';
            break;
        case '2':
            textoModal = 'Deseja realmente finalizar este estágio e enviá-lo para o avaliação?';
            break;
        case '3':
            textoModal = 'Deseja realmente enviar este estágio para deferimento?';
            break;
        case '4':
            textoModal = 'Deseja realmente deferir este estágio e sua avaliação?';
            break;
        case '5':
            textoModal = 'Deseja realmente excluir este estágio?';
            break;
        default:
            return;
    }
    $( "#modal-confirm .modal-body" ).text( textoModal );
    confirmModal(textoModal, function () {
        $.ajax('./paginas/alteradados.php', {
            method: 'post',
            data: {
                cod: cod,
                estado: tostate
            }, success: function ( response ) {
                showMessage( 'Estágio alterado com sucesso!' );
                if ($parentDiv.attr('id') == 'div-btn-finalizar') {
                    window.location.href = 'index.php';
                } else {
                    loadTabelaMeusEstagios();
                }
            }, error: function ( response ) {
                showError( response );
            }
        });
    });
}

$('#table-estagios-aluno').on( 'click', '.btn-alterar', btnAlterarEstado);
$('#div-btn-finalizar').on( 'click', '.btn-alterar', btnAlterarEstado);


function loadTabelaMeusEstagios () {
    $.ajax( './paginas/carregameusestagios.php', {
        method: 'get',
        data: { tp: 'aluno' },
        success: function ( response ) {
            var estagios = JSON.parse( response ).estagios;
            $('#table-estagios-aluno tbody').empty();
            for (var i = 0; i < estagios.length; i++ ) {
                var estagio = estagios[i];
                var $currentTr = $('<tr>').attr('cod',estagio.id);

                /* BOTOES DE ACAO */
                var $btnGroup = $('<div>').addClass('btn-group');
                $btnGroup.append('<button type="button" class="btn btn-default">Ações</button>');
                $dropDownButton = $('<button type="button">')
                    .addClass('btn btn-default dropdown-toggle')
                    .attr('data-toggle','dropdown')
                    .append('<span class="caret">');
                $dropdownUl = $('<ul class="dropdown-menu">');
                $btnGroup.append($dropDownButton);
                $btnGroup.append($dropdownUl);
                if (estagio.estado.id == 1) { //em desenvolvimento
                    //$dropdownUl.append('<li><a class="btn-editar" cod="' + estagio.id + '"><button type="button" class="btn btn-primary">EDITAR <span class="glyphicon glyphicon-pencil"></span></button></a></li>');
                    //$dropdownUl.append('<li><a href="#" class="btn-alterar" cod="' + estagio.id + '" estado="2"><button type="button" class="btn btn-success">FINALIZAR</button></a></li>');
                    $dropdownUl.append('<li><a class="li-blue btn-editar" cod="' + estagio.id + '">EDITAR <span class="glyphicon glyphicon-pencil"></span></a></li>');
                    $dropdownUl.append('<li><a class="li-green btn-alterar" cod="' + estagio.id + '" estado="2">FINALIZAR <span class="glyphicon glyphicon-check" ></span></a></li>');
                } else if ( estagio.estado.id != 5 ) {
                    //$dropdownUl.append('<li><a href="#" class="btn-alterar" cod="' + estagio.id + '" estado="1"><button type="button" class="btn btn-primary">CORRIGIR</button></a></li>');
                    $dropdownUl.append('<li><a class="li-red btn-alterar" cod="' + estagio.id + '" estado="1">CORRIGIR <span class="glyphicon glyphicon-pencil"></span></a></li>');
                }
                $('<td>').append($btnGroup).appendTo($currentTr);

                /* CURSO */
                $currentTr.append('<td>' + estagio.curso.descricao + '</td>');

                /* PROFESSOR */
                $currentTr.append('<td>' + estagio.professor.fullName + '</td>');

                /* SITUACAO */
                var situacaoStr = estagio.estado.descricao;
                if (estagio.nota && (estagio.estado.id == 3 || estagio.estado.id == 4)) {
                    var spanClass = estagio.nota >= 7 ? 'span-green' : 'span-red';
                    situacaoStr += '<span class="' + spanClass + '"> (Nota <strong>' + estagio.nota + '</strong>)</span>';
                }
                $currentTr.append('<td>' + situacaoStr + '</td>');

                /* ANOTACOES */
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
                    showMessage( getHTMLAnotacoes( anotacoes , true ) );
                } );
                $tdAnotacoes.append($anotacoesBtn);
                $currentTr.append($tdAnotacoes);

                /* CERTIFICADO */
                /*
                $tdCertificado = $('<td>');
                if (estagio.token && estagio.estado.id == 4) {
                    $tdCertificado.addClass('td-btn');
                    $anchorCertificado = $('<a>');
                    $anchorCertificado.attr('href','./paginas/getcertificado.php?codestagio='+estagio.id);
                    $anchorCertificado.append('<span class="glyphicon glyphicon-education span-blue">');
                    $tdCertificado.append($anchorCertificado);
                } else {
                    $tdCertificado.text(( estagio.estado.id != 4 ) ? 'N/A' : 'Reprovado');
                }
                //'<td class="td-btn"><a href="./paginas/getfile.php?codarq='.$arquivo->getId().'"><span cod="' . $arquivo->getId() . '" class="glyphicon glyphicon-floppy-save span-baixar-arquivo"></span></a></td>'
                $currentTr.append($tdCertificado);
                */

                $('#table-estagios-aluno tbody').append($currentTr);

            }
        }, error: function ( response ) {
        }
    });
}
loadTabelaMeusEstagios();