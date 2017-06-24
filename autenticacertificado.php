<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 23/07/16
 * Time: 13:05
 */
ini_set('default_charset','utf-8');
?>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/autenticacertificado.css">
    <title></title>
</head>
<body>
<?
$found = true;
if (isset($_GET) && isset($_GET['token'])) {
    $token = '';
    foreach ( $_GET['token'] as $tkn ) {
        $token .= strtoupper($tkn);
    }
    //echo 'procurou ' . $token . '<br/>achou:';
    require_once ("Estagio.php");
    $estagio = Estagio::getByToken($token);
    if ($estagio->getId() == null) {
        $found = false;
    } else {
        $certPath = $estagio->getCertificadoPath();
        header('Content-Disposition: attachment; filename="certificado.pdf"');
        readfile($certPath);
    }
}
if (! $found) {
    echo '<span> certificado n&atilde;o encontrado<br/></span>';
}
?>
<h3>Digite o token de autentica&ccedil;&atilde;o</h3>
<form method="get" class="form-inline" role="form">
    <? for ($i = 0; $i < 8; $i++) { ?>
        <div class="form-group">
            <input cod="<?=$i?>" maxlength="4" class="form-control auth-input" type="text" name="token[]" id="token_<?=$i?>">
            <label class="ponto-separador" for="token_<?=$i?>" ><?=$i == 7 ? '' : '.'?></label>
        </div>
    <? } ?>
    <input id="btn-submit" type="submit" value="Enviar">
</form>
</body>
<script type="application/javascript" language="javascript" src="js/jquery/jquery-2.2.1.min.js"></script>
<script type="application/javascript" language="javascript" src="js/bootstrap/bootstrap.min.js"></script>
<script type="application/javascript" language="javascript" src="js/autenticacertificado.js"></script>
</html>
