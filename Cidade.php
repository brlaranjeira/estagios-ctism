<?php

/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 16/11/16
 * Time: 15:41
 */
class Cidade {


    private $id;
    /**
     * @type UF
     */
    private $uf;
    private $nome;

    /**
     * Cidade constructor.
     * @param $id
     * @param UF $uf
     * @param $nome
     */
    public function __construct($id, $uf, $nome) {
        $this->id = $id;
        require_once ("UF.php");
        $this->uf = (is_object($uf)) ? $uf : UF::getByID($uf);
        $this->nome = $nome;
    }

    public static function getById( $id ) {
        $sql = 'SELECT uf, nome FROM cidade WHERE id = ?';
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($id));
        $res = $stmt->fetchObject();
        return new Cidade($id, $res->uf, $res->nome);
    }

    public static function getAll() {
        $sql = 'SELECT id, uf, nome FROM cidade';
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array());
        $results = $stmt->fetchAll();
        $ret = array();
        foreach ($results as $result) {
            $ret[] = new Cidade($result['id'],$result['uf'],$result['nome']);
        }
        return $ret;
    }

    public function asJSON() {
        $ret = '{"id": "' . $this->id . '",';
        $ret .= '"uf": ' . $this->uf->asJSON() . ',';
        $ret .= '"nome": "' . $this->nome . '"}';
        return $ret;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UF
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }



    public function __toString() {
        return $this->asJSON();
    }


}