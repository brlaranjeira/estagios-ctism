<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 13/04/2016
 * Time: 09:49
 */

session_start();
$diretorio = dirname($_SERVER['PHP_SELF']) . '/';

if (empty($_SESSION['ctism_user'])) {
	header('Location: ' . $diretorio . 'login.php');
}
header('Location: ' . $diretorio . 'main.php');