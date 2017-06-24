/**
 * Created by bruno on 28/07/16.
 */

$('.auth-input').on('paste', function (evt) {
    var input = evt.originalEvent.clipboardData.getData('text').replace(/\./g,'');
    $element = $(this);
    var cod = parseInt($element.attr('cod'));
    var size = $element.attr('maxlength');
    var previous = $element.val();
    var others = input.substr(size-previous.length);
    var regex = new RegExp('.{1,'+size+'}','g');
    var split = others.match(regex);
    if (split.length > 0) {
        $('.auth-input').each( function () {
            if (parseInt($(this).attr('cod')) > cod && split.length > 0) {
                $(this).val(split[0]);
                split.shift();
            }
        } );
    }


})

$('.auth-input').on( 'input' , function () {
    var $element = $(this);
    var value = $element.val();
    var maxlength = $element.attr('maxlength');
    var cod = $element.attr('cod');
    if (value.length == maxlength) {
        var nextCod = parseInt(cod) + 1;
        var $next = $('.auth-input[cod=' + nextCod + ']');
        if ($next.length > 0) {
            $next[0].focus();
        } else {
            $('#btn-submit').focus();
        }
    }
});