<?php

/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 16/11/16
 * Time: 15:42
 */
class UF {

    private $id;
    private $nome;

    public function __construct( $id , $nome ) {
        $this->id = $id;
        $this->nome = $nome;
    }

    public static function getByID( $id ) {
        $sql = 'SELECT nome FROM uf WHERE id = ?';
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($id));
        $res = $stmt->fetchObject();
        return new UF($id, $res->nome);
    }

    /**
     * @return UF[]
     */
    public static function getAll() {
        $sql = 'SELECT * FROM uf';
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array());
        $res = $stmt->fetchAll();
        $ret = array();
        foreach ( $res as $uf ) {
            $ret[] = new UF($uf['id'],$uf['nome']);
        }
        return $ret;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNome() {
        return $this->nome;
    }


    /**
     * @return Cidade[]
     */
    public function getCidades() {
        $sql = 'SELECT id , nome FROM cidade WHERE uf = ? ORDER BY nome ASC';
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($this->id));
        $res = $stmt->fetchAll();
        $ret = array();
        require_once ("Cidade.php");
        foreach ( $res as $cid ) {
            $ret[] = new Cidade($cid['id'],$this->id,$cid['nome']);
        }
        return $ret;
    }


    public function asJSON() {
        $ret = '{"id": "' . $this->id . '",';
        $ret .= '"nome": "' . $this->nome . '"}';
        return $ret;
    }

}