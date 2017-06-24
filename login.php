<!DOCTYPE html>
<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 11/04/2016
 * Time: 13:21
 */
ini_set('default_charset','utf-8');
if (!empty($_POST)) {
	$_POST['uid'] = trim($_POST['uid']);
    require_once ("lib/LDAP/ldap.php");
    $ldap = new ldap();
    if ($ldap->auth($_POST['uid'],$_POST['pwd'])) {
        session_start();
        require_once ("Usuario.php");
        $usuario = new Usuario($_POST['uid'],true);
        $_SESSION['ctism_user'] = serialize($usuario);
        header('Location: main.php');
    } else {
        $errorMessage = 'Usuário ou senha inválidos';
    }
}

?>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <? require_once ("ConfigClass.php"); ?>
    <title><?=ConfigClass::sysName?> - Login</title>
</head>
<body>
    <div class="container-fluid">
        <h3>Login</h3>
        <? if (!empty($errorMessage)) {
            ?><h6 class="text-danger"><?=$errorMessage?></h6><?
        } ?>
        <form role="form" class="form-horizontal" method="post">
            <div class="row">
                    <div class="form-group">
                        <label class="control-label col-xs-3" for="uid">Usuário</label>
                        <div class="col-xs-3">
                            <input type="text" class="form-control" name="uid" id="uid"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label class="control-label col-xs-3" for="pwd">Senha</label>
                        <div class="col-xs-3">
                            <input type="password" class="form-control" name="pwd" id="pwd"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-3 col-xs-offset-3">
                            <input type="submit" class="form-control" value="Entrar!"/>
                        </div>
                    </div>
                </div>

        </form>
    </div>
</body>
<script type="application/javascript" language="javascript" src="js/bootstrap/bootstrap.min.js"></script>
</html>
