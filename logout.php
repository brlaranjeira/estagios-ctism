<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 26/04/2016
 * Time: 12:56
 */
session_start();
session_destroy();
session_commit();
header('Location: login.php');