<?php

/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 25/05/2016
 * Time: 10:59
 */
class Estado {

	const EM_DESENV = 1;
	const AGUARD_AVAL = 2;
	const AGUARD_DEFER = 3;
	const DEFERIDO = 4;
	const EXCLUIDO = 5;

	private $id;
	private $descricao;

	/**
	 * Estado constructor.
	 *
	 * @param $id
	 * @param $descricao
	 */
	public function __construct($id, $descricao) {
		$this->id = $id;
		$this->descricao = $descricao;
	}

    public static function getAll() {
        $sql = "SELECT id, descricao FROM estado";

        require_once ("ConexaoBD.php");
        $statement = ConexaoBD::getConnection()->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        $ret = array();
        foreach ( $results as $result ) {
            $ret[] = new Estado($result['id'],$result['descricao']);
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
	public function getDescricao() {
		return $this->descricao;
	}

	/**
	 * @param $id
	 *
	 * @return Estado
	 */
	public static function getById($id) {
		$sql = "SELECT id, descricao FROM estado WHERE id=?";
		require_once ("ConexaoBD.php");
		$statement = ConexaoBD::getConnection()->prepare($sql);
		$statement->execute(array($id));
		$ret = $statement->fetchObject();
		return new Estado($ret->id,$ret->descricao);
	}

	function __toString() {
		return "{\"id\": \"$this->id\",\"descricao\":\"$this->descricao\"}";
	}


}