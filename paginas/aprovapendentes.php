<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 13/04/2016
 * Time: 13:10
 */

require_once ("Usuario.php");
require_once ("Paginas.php");
Paginas::forcaSeguranca();

require_once ("Grupos.php");
$usuarios = Usuario::getAllFromGroups(Grupos::BOLSISTAS);