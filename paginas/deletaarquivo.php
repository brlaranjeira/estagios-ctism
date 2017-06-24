<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20/06/16
 * Time: 18:51
 */
ini_set('default_charset','utf-8');
session_start();
require_once ("../Usuario.php");
$usuario = Usuario::unserialize($_SESSION['ctism_user']);
require_once ('../Paginas.php');

//Paginas::forcaSeguranca();

require_once ('../Usuario.php');
require_once ('../Arquivo.php');
$a = Arquivo::getById($_REQUEST['codarq']);
require_once ("../Estagio.php");
if (Estagio::getById($a->getIdEstagio())->getAluno() == $usuario->getUid()) {
    $deleteOk = $a->delete();
    echo $deleteOk ? 'Arquivo removido com sucesso' : 'Erro ao remover o arquivo';
} else {
    http_response_code(403);
    die('Você não pode acessar esta página.');
}
