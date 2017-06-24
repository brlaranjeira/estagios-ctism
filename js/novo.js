/**
 * Created by bruno on 28/09/16.
 */

var empresaRecuperada;
$('input.input-date').mask('00/00/0000');
$('#btn-novo-estagio').click( function() {
    var temErro = false;
    $('#form-novo-estagio').find('select,input[type=date]').each( function () {
        if ($(this).val().length == 0) {
            $(this).parent().addClass('has-error');
            temErro = true;
        }
    });
    $('.input-group-empresa').each( function () {
        if ($(this).find('input').val().length == 0) {
            $(this).parent().addClass('has-error');
            temErro = true;
        }
    });
    if (temErro) {
        showError('Há erro(s) no preenchimento.');
    } else {
        var formdata = $('#form-novo-estagio').serializeArray();
        $.ajax( './paginas/' + $('#form-novo-estagio').attr('action'), {
            data: formdata,
            method: $('#form-novo-estagio').attr('method'),
            success: function ( response ) {
                showMessage( response );
                loadMainContent({pg:'novo'});
            }, error: function ( response ) {

            }
        } );
    }
});
$('input.carga_horaria').mask('00');

//$('#form-novo-estagio').on( 'click', '.input-empresa', adicionaEmpresa );
$('#form-novo-estagio').on( 'focus', '.input-empresa', function () {
//$('.input-empresa').click(function () {
    var empid = $(this).parent().attr('cod');
    document.getElementById('emp-id').value = empid;
    $('#modal-busca-empresa').on('shown.bs.modal',function () {
        $('#emp-cnpj').focus();
    });
    $('#modal-busca-empresa').find('input[type=text],select').val('').not('#emp-cnpj').attr('disabled','true');
    $('#modal-busca-empresa').modal('show');
});


$('#emp-cnpj').mask($('#emp-cnpj').attr('mask'));
$('#emp-cnpj').on('input', CNPJOnType );

$('#emp-cep').mask($('#emp-cep').attr('mask'));
$('#emp-cep').on('input', CEPOnType );
$('select,input').not('[type=hidden]').on( 'change' , function () {
    $(this).parent().removeClass('has-error');
});

$('#emp-uf').change(loadCidades);

$('#form-novo-estagio').on( 'click', '.span-nova-empresa' , adicionaEmpresa );

$('#btn-confirma-empresa').click( confirmaEmpresa );

function adicionaEmpresa ( ) {
    var $deletar = $('.span-nova-empresa');
    var $ant = $('.span-nova-empresa').parentsUntil('.row').last().parent();
    $prox = $ant.clone(true);
    $prox.find('input').val('');
    $prox.find('.well').removeClass('has-error');
    var cod = $ant.find('.input-group').attr('cod');
    $prox.find('.input-group').attr('cod',parseInt(cod,10)+1);
    $ant.after($prox);
    $deletar.remove();
}

$('#form-novo-estagio').on('click', '.span-remove-empresa', function () {
    var c = $(this).parent().attr('cod');
    var $inputGroup = $(this).parentsUntil('.input-group-empresa').length == 0 ? $(this).parent() : $(this).parentsUntil('.input-group-empresa').last().parent();
    if ($('.input-group-empresa').length == 1) {
        $inputGroup.find('input').val('');
    } else {
        var $btnNova = $inputGroup.find('.span-nova-empresa');
        if ($btnNova.length > 0) { //ultimo
            var $btnNovaClone = $btnNova.clone();
            $btnNova.remove();
            var $row = $(this).parentsUntil('.row').length == 0 ? $(this).parent() : $(this).parentsUntil('.row').last().parent();
            $row.remove();
            $('.input-group-empresa').last().append($btnNovaClone);
        } else {
            var $row = $(this).parentsUntil('.row').length == 0 ? $(this).parent() : $(this).parentsUntil('.row').last().parent();
            $row.remove();
        }
        var count = 0;
        $('.input-group-empresa').each( function () {
            $(this).attr('cod',count);
            count++;
        } );
    }
});



function toTitleCase ( str ) {
    var arr = str.split(' ');
    var ret = '';
    for (var i = 0; i < arr.length; i++) {
        ret += arr[i][0].toUpperCase() + arr[i].substr(1).toLowerCase() + ' ';
    }
    return ret;
}
