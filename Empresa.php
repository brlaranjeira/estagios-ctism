<?php

/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 16/11/16
 * Time: 15:38
 */
class Empresa {

    private $id;
    private $cnpj;
    private $razaoSocial;

    /**
     * @type Cidade
     */
    private $cidade;

    private $cep;
    private $nro;
    private $bairro;
    private $complemento;
    private $logradouro;

    private $carga_horaria;
    private $supervisor;


    /**
     * Empresa constructor.
     * @param $id
     * @param $cnpj
     * @param $razaoSocial
     * @param Cidade $cidade
     * @param $cep
     * @param $nro
     * @param $bairro
     * @param $complemento
     * @param $logradouro
     */
    public function __construct($id = null, $cnpj, $razaoSocial, $cidade, $cep, $nro, $bairro, $complemento, $logradouro, $carga_horaria=null, $supervisor = null) {
        $this->id = $id;
        $this->cnpj = $cnpj;
        $this->razaoSocial = $razaoSocial;
        $this->cep = $cep;
        $this->nro = $nro;
        $this->bairro = $bairro;
        $this->complemento = $complemento;
        $this->logradouro = $logradouro;
        if (isset($carga_horaria)) {
            $this->carga_horaria = $carga_horaria;
        }
        if (isset($supervisor)) {
            $this->supervisor = $supervisor;
        }
        require_once ("Cidade.php");
        $this->cidade = is_object($cidade) ? $cidade : Cidade::getById($cidade);
    }

    public static function getAll() {
        $sql = 'SELECT id,cnpj,cidade,cep,numero,bairro,complemento,logradouro,razao_social FROM empresa';
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array());
        $results = $stmt->fetchAll();
        $ret = array();
        foreach ( $results as $result ) {
            $ret[] = new Empresa($result['id'],$result['cnpj'],$result['razao_social'],$result['cidade'],$result['cep'],$result['numero'],$result['bairro'],$result['complemento'],$result['logradouro']);
        }
        return $ret;
    }


    /**
     * @param $id
     * @return Empresa
     */
    public static function getById( $id ) {
        $sql = 'SELECT cnpj,cidade,cep,numero,bairro,complemento,logradouro,razao_social FROM empresa WHERE id = ?';
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($id));
        $result = $stmt->fetchObject();
        return new Empresa($id,$result->cnpj,$result->razao_social,$result->cidade,$result->cep,$result->numero,$result->bairro,$result->complemento,$result->logradouro);
    }

    /**
     * @param $cnpj
     * @return Empresa[]
     */
    public static function getByCNPJ( $cnpj ) {
        $sql = 'SELECT id,cidade,cep,numero,bairro,complemento,logradouro,razao_social FROM empresa WHERE cnpj = ? ORDER BY id DESC';
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        $stmt = $conn->prepare($sql);
        $cnpj = str_replace(array('.','-','/'),'',$cnpj);
        $stmt->execute(array($cnpj));
        $results = $stmt->fetchAll();
        $ret = array();
        foreach ( $results as $result ) {
            $ret[] = new Empresa($result['id'],$cnpj,$result['razao_social'],$result['cidade'],$result['cep'],$result['numero'],$result['bairro'],$result['complemento'],$result['logradouro']);
        }
        return $ret;
    }

    public function save() {
        require_once ("ConexaoBD.php");
        $conn = ConexaoBD::getConnection();
        if ($conn->inTransaction()) {
            return null;
        }
        $conn->beginTransaction();
        if (isset($this->id)) {//update
            $sql = 'UPDATE empresa SET cnpj = ?, cidade = ?, cep = ?, numero = ?, bairro = ?, complemento = ?, logradouro = ?, razao_social = ? WHERE id = ?';
            $stmt = $conn->prepare($sql);
            $execOk = $stmt->execute(array($this->cnpj,$this->cidade->getId(),$this->cep,$this->nro,$this->bairro,$this->complemento,$this->logradouro,$this->razaoSocial,$this->id));
            if ($execOk && $conn->commit()) {
                return true;
            }
            $conn->rollBack();
            return null;
        } else {//insert
            $sql = 'INSERT INTO empresa (cnpj, cidade, cep, numero, bairro, complemento, logradouro, razao_social) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($sql);
            $oarray = array($this->cnpj,$this->cidade->getId(),$this->cep,$this->nro,$this->bairro,$this->complemento,$this->logradouro,$this->razaoSocial);
            $execOk = $stmt->execute($oarray);
            if (!$execOk) {
                $conn->rollBack();
                return null;
            }
            $idInserido = $conn->lastInsertId('id');
            if ($conn->commit()) {
                $this->id = $idInserido;
                return $this->id;
            }
            $conn->rollBack();
            return null;
        }
    }

    public function asJSON() {
        $str = '{';
        $first = true;
        foreach ( get_object_vars($this) as $k => $v ) {
            $str .= !$first ? ',' : '';
            $str .=  "\"$k\":";
            $str .= is_object($v) ? $v : "\"$v\"";
            $first = false;
        }
        return $str . '}';
    }

    public function setRazaoSocial($razaoSocial) {
        $this->razaoSocial = $razaoSocial;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCnpj($format = false) {
        if ($format) {
            return Utils::mask('##.###.###/####-##',$this->cnpj);
        }
        return $this->cnpj;
    }

    /**
     * @return mixed
     */
    public function getRazaoSocial() {
        return $this->razaoSocial;
    }

    /**
     * @return Cidade
     */
    public function getCidade() {
        return $this->cidade;
    }

    /**
     * @return mixed
     */
    public function getCep() {
        return $this->cep;
    }

    /**
     * @return mixed
     */
    public function getNro() {
        return $this->nro;
    }

    /**
     * @return mixed
     */
    public function getBairro() {
        return $this->bairro;
    }

    /**
     * @return mixed
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * @return mixed
     */
    public function getLogradouro() {
        return $this->logradouro;
    }

    /**
     * @return null
     */
    public function getCargaHoraria() {
        return $this->carga_horaria;
    }

    /**
     * @return null
     */
    public function getSupervisor() {
        return $this->supervisor;
    }

    public function __toString() {
        return $this->asJSON();
    }

    /**
     * @param null $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param mixed $cnpj
     */
    public function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    /**
     * @param Cidade $cidade
     */
    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    /**
     * @param mixed $cep
     */
    public function setCep($cep) {
        $this->cep = $cep;
    }

    /**
     * @param mixed $nro
     */
    public function setNro($nro) {
        $this->nro = $nro;
    }

    /**
     * @param mixed $bairro
     */
    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    /**
     * @param mixed $complemento
     */
    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    /**
     * @param mixed $logradouro
     */
    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    /**
     * @param null $carga_horaria
     */
    public function setCargaHoraria($carga_horaria) {
        $this->carga_horaria = $carga_horaria;
    }

    /**
     * @param null $supervisor
     */
    public function setSupervisor($supervisor) {
        $this->supervisor = $supervisor;
    }

    private function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } elseif(isset($mask[$i])) {
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }


}