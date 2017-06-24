<?php

/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 12/04/2016
 * Time: 12:56
 */
class Paginas {

    static function forcaSeguranca() {
        (session_status() != PHP_SESSION_ACTIVE) and session_start();
        require_once ("Usuario.php");
        $usuario = Usuario::unserialize($_SESSION['ctism_user']);
        if ( basename($_SERVER['SCRIPT_NAME']) != 'getpage.php' || !self::checkPermissao($usuario, $_GET['pg']) ) {
            http_response_code(403);
            if ( basename($_SERVER['SCRIPT_NAME']) != 'getpage.php' ) {
                header( 'Location: index.php' );
            }
            die('Você não pode acessar esta página.');
        }
    }

    /**
     * @param $usuario Usuario
     * @param $pagina string nome da pagina verificada
     * @return boolean se a pagina eh permitida ou nao
     */
    static function checkPermissao($usuario,$pagina) {
        require_once ("ConexaoBD.php");
        $gidArray = $usuario->getGrupos();
        $sql = 'SELECT COUNT(*) AS cnt FROM permissoes WHERE permissao = 1 AND idpagina=? AND ( idgrupo = \'*\' OR idgrupo IN (' . str_repeat ('?, ',  count ($gidArray) - 1) . '?))';
        $statement = ConexaoBD::getConnection()->prepare($sql);
        array_unshift($gidArray,$pagina);
        $statement->execute($gidArray);
        $rows = $statement->fetchAll();
        foreach ($rows as $row) {
            return $row['cnt'] > 0;
        }
        return false;
    }

    /**
     * @param $usuario Usuario
     * @return array paginas permitidas
     */
    static function getAllowedPages($usuario) {
        require_once ("ConexaoBD.php");
        $gidArray = $usuario->getGrupos();
        $ret = array();
        try {
            $sql = 'SELECT DISTINCT PP.* FROM permissoes P LEFT JOIN paginas PP ON P.idpagina = PP.idpagina WHERE PP.visivel = 1 AND P.idgrupo IN (' . str_repeat ('?, ',  count ($gidArray) - 1) . '?)';
            $statement = ConexaoBD::getConnection()->prepare($sql);
            $statement->execute($gidArray);
            $results = $statement->fetchAll();
            foreach ($results as $result) {
                $current = array();
                $current['id'] = $result['idpagina'];
                $current['nome'] = $result['nmpagina'];
                $ret[] = $current;
            }
        } catch (Exception $ex) {
            die();
        } finally {
            return $ret;
        }

    }


}