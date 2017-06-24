<?php

/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 24/05/2016
 * Time: 17:38
 */
class Curso {

	private $id;

	private $descricao;

	/**
	 * Curso constructor.
	 *
	 * @param $id
	 * @param $descricao
	 */
	public function __construct($id, $descricao) {
		$this->id = $id;
		$this->descricao = $descricao;
	}


	/**
	 * @param $id
	 *
	 * @return Curso
	 */
	public static function getById($id) {
		$sql = "SELECT id, descricao FROM curso WHERE id=?";
		require_once ("ConexaoBD.php");
		$statement = ConexaoBD::getConnection()->prepare($sql);
		$statement->execute(array($id));
		$ret = $statement->fetchObject();
		return new Curso($ret->id,$ret->descricao);
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
	 * @return Curso[] todos os cursos
	 */
	public static function getAll() {

		$sql = "SELECT id, descricao FROM curso";

		require_once ("ConexaoBD.php");
		$statement = ConexaoBD::getConnection()->prepare($sql);
		$statement->execute();
		$results = $statement->fetchAll();
		$ret = array();
		foreach ( $results as $result ) {
			$ret[] = new Curso($result['id'],$result['descricao']);
		}
		return $ret;
	}

	function __toString() {
		return "{\"id\": \"$this->id\",\"descricao\":\"$this->descricao\"}";
	}


}

