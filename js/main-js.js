function getParam( paramName ) {
    var ret = null;
    window.location.search.substr(1).split('&').forEach( function ( elem ) {
        var parts = elem.split('=');
        if (parts[0] === paramName){
            ret = parts[1];
        }
    });
    return ret;
}
function getAllParams () {
    var ret = {};
    window.location.search.substr(1).split('&').forEach( function ( elem ) {
        var parts = elem.split('=');
        ret[parts[0]] = parts[1];
    });
    return ret;
}

function loadMainContent ( params ) {
    var url = 'getpage.php';
    if (params !== undefined && params.length > 0) {
        url += '?' + params;
    }
    showCarregando();
    $.ajax( url, {
        data: params,
        success: function( response ) {
            var searchPart = '?';
            var keys = Object.keys(params);
            var count = 0;
            for(var i=0;i<keys.length;i++) {
                if (keys[i].length > 0) {
                    if (count != 0) {
                        searchPart += '&';
                    }
                    searchPart += keys[i] + '=' + params[keys[i]];
                    count++;
                }
            }
            history.pushState('','','main.php'+searchPart);
            $('li.li-nav.active').removeClass('active');
            $( 'li.li-nav[cod=' + params.pg + ']' ).addClass('active');
            $('#div-content').html( response );
            hideCarregando();
        }, error: function ( response ) {
            var str = 'Erro!<br/>';
            str += '[' + response.status + '] ' + response.statusText;
            $('#div-content').html(str);
            $('li.li-nav.active').removeClass('active');
            $('li.li-nav').first().addClass('active');
            //history.pushState('','','main.php?pg=' + $('li.li-nav.active').attr('cod'));
            hideCarregando();
            if (response.responseText.length > 0 ) {
                showError( response.responseText + '<br/>Aguarde...' );
            }
            setTimeout(function(){window.location.href="/index.php";},response.responseText.length > 0 ? 3000 : 0);
        }, complete: function() {
            var defaultTitle = 'Registros de Est&eaacute;gio';
            $.ajax('paginas/gettitle.php', {
                success: function ( response ) {
                    $('title').html( response + ' - ' + $('li.li-nav.active > a').text() );
                }, error: function () {
                    $('title').html('Registros de Est&aacute;gio - ' + $('li.li-nav.active > a').text() );
                }
            });
        }
    });
}

var allParams = getAllParams();

var gotoPage = getParam('pg');
if (gotoPage == null) {
    gotoPage = $('li.li-nav.active').attr('cod');
}
allParams.pg = gotoPage;
loadMainContent(allParams);

function showCarregando() {
    $('#modal-carregando').modal("show");
}
function hideCarregando() {
    $('#modal-carregando').modal("hide");
}
function showError( str ) {
    $('#p-err-message').html( str );
    $('#modal-error').modal('show');
}

function showMessage( str ) {
    $('#modal-mensagem .modal-body').html( str );
    $('#modal-mensagem').modal('show');
}

/**
 *
 * @param mensagem texto para exibir
 * @param callbackOk callback do botao ok
 * @param callbackCancel callback do botao cancel
 * @param labelOk label do botao ok. Default: Sim
 * @param labelCancel label do botao cancel. Default: Não
 */
function confirmModal( mensagem, callbackOk, callbackCancel, labelOk, labelCancel ) {

    /* LABEL PRINCIPAL */
    mensagem = mensagem === undefined ? 'Confirma?' : mensagem;
    $('#modal-confirm .modal-body').html( mensagem );

    /* BOTAO OK */
    labelOk = labelOk === undefined ? 'Sim' : labelOk;
    $('#btn-confirm-ok').text(labelOk);
    $('#btn-confirm-ok').unbind('click');
    $('#btn-confirm-ok').click( function () {
        $('#modal-confirm').modal('hide');
        $('#modal-confirm').on('hidden.bs.modal', callbackOk);
    } );

    /* BOTAO CANCELA */
    labelCancel = labelCancel === undefined ? 'Não' : labelCancel;
    $('#btn-cancela-ok').text( labelCancel );
    $('#btn-cancela-ok').unbind('click');
    $('#btn-cancela-ok').click(function () {
        $('#modal-confirm').modal('hide');
        $('#modal-confirm').on('hidden.bs.modal', callbackCancel ? callbackCancel : function () {});
    });

    /* DESABILITA EVENTO APOS HIDE E MOSTRA */
    $('#modal-confirm').off('hidden.bs.modal');
    $('#modal-confirm').modal('show');

}


$('li.li-nav').click( function () {
    var params = {
        'pg' : $(this).attr('cod')
    };
    loadMainContent( params );
});

function getHTMLAnotacoes (anotacoes, showFile ) {
    var lastDate = '';
    var txt = '';
    for  ( var i = 0; i < anotacoes.length; i++ ) {
        var anotacao = anotacoes[i];
        var dtHr = anotacao.dttime.split(' ');
        if (dtHr[0] != lastDate) {
            txt += '<span class="span-msgdata">'+dtHr[0] + '</span>';
        }
        txt += '<span class="span-msginfo">' + dtHr[1] + ' - ' + anotacao.autor.fullName + ': </span>';
        if (showFile && anotacao.id_arquivo.length) {
            txt += '<span class="span-msgfile">Anotação sobre o arquivo <a class="fileanchor" cod="' + anotacao.id_arquivo + '" href="./paginas/getfile.php?codarq=' + anotacao.id_arquivo + '">(Carregando nome do arquivo...)</a></span>';
            $.ajax('./paginas/getfileiddescr.php', {
                data: {codarq: anotacao.id_arquivo},
                success: function ( response ) {
                    var responseObj = JSON.parse( response );
                    $('a.fileanchor[cod='+responseObj.id+']').text(responseObj.descr);
                }
            });
        }

        if (anotacao.texto.length) {
            txt += '<span class="span-msgbody">' + anotacao.texto + '</span>';
        } else {
            txt += '<span class="span-msgbody msg-vazia"> Anota&ccedil;&atilde;o vazia </span>';
        }
        lastDate = dtHr[0];
    }
    return txt.length ? txt : 'Nenhuma anotação';
}

