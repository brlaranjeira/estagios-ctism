<?php

/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 30/05/2016
 * Time: 09:54
 */
class Arquivo {

	private $id;
	private $caminho;
	private $visivelAluno;
	private $idEstagio;


	public function __construct($fileName, $idEstagio, $descricao, $visivelAluno = true, $id = null) {
		$this->id = $id;
		$this->idEstagio = $idEstagio;
		$this->visivelAluno = $visivelAluno;
		if ( !isset($id) ) {
			if (sizeof(explode('.',$fileName)) > 1) {
				$descricao .= '.' . end(explode('.',$fileName));
			}
			require_once ("Estagio.php");
			$this->caminho = time() . '.' . $descricao;
		} else {
			$this->caminho = $fileName;
		}
	}

	public function moveToDir( $tmpName ) {

	    require_once ("ConfigClass.php");
		if (!file_exists(ConfigClass::diretorioArquivos) or !is_dir(ConfigClass::diretorioArquivos)) {
			mkdir(ConfigClass::diretorioArquivos);
		}
		if ( ! move_uploaded_file($tmpName , ConfigClass::diretorioArquivos . '/' . $this->caminho) ) {
			throw new RuntimeException('Erro ao mover o arquivo');
		}
	}


	public function saveOrUpdate() {
		if (isset($this->id)) {//update

		} else { //insert
			$sql = 'INSERT INTO arquivo (caminho,visivel_aluno,id_estagio) values ( ? , b? , ? )';
			require_once ("ConexaoBD.php");
			$conexao = ConexaoBD::getConnection();
			if ($conexao->inTransaction()) {
				return null;
			}
			$conexao->beginTransaction();
			$statement = $conexao->prepare($sql);
			$oarray = array( $this->caminho , $this->visivelAluno ? 1 : 0 , $this->idEstagio );
			$statement->execute($oarray);
			$idInserido = $conexao->lastInsertId('id');
			if ($conexao->commit()) {
				$this->id = $idInserido;
				return $this->id;
			}
			$conexao->rollBack();
			return null;
		}
	}

	/**.
	 * @param $estagio Estagio|int estagio
	 * @param $isAluno boolean se eh um aluno, para retornar ou nao os arquivos invisiveis
	 *
	 * @return Arquivo[] arquivos do estagio
	 */
	public static function getAllForEstagio ( $estagio , $isAluno = true ) {
		$estagio = (is_object($estagio)) ? $estagio->getId() : $estagio;
		$camposSelect = $isAluno ? ' id , caminho ' : ' id , caminho , visivel_aluno ';
		$condicoes = $isAluno ? ' id_estagio = ? AND visivel_aluno = 1 ' : ' id_estagio = ? ';
		$sql = 'SELECT ' . $camposSelect . ' FROM arquivo WHERE ' . $condicoes;
		require_once ("ConexaoBD.php");
		$conexao = ConexaoBD::getConnection();
		$statement = $conexao->prepare($sql);
		$statement->execute(array($estagio));
		$results = $statement->fetchAll();
		$ret = array();
		foreach ($results as $result) {
			$ret[] = new Arquivo( $result['caminho'] , $estagio , null , $isAluno ? true : $result['visivel_aluno'] , $result['id'] );
		}
		return $ret;
	}

	/**
	 * @param $id
	 * @return Arquivo
	 */
	public static function getById ($id ) {
		$sql = "SELECT caminho, visivel_aluno, id_estagio FROM arquivo WHERE id=?";
		require_once ("ConexaoBD.php");
		$statement = ConexaoBD::getConnection()->prepare($sql);
		$statement->execute(array($id));
		$ret = $statement->fetchObject();
		///$fileName, $idEstagio, $descricao, $visivelAluno = true, $id = null
		return new Arquivo($ret->caminho,$ret->id_estagio,null,$ret->visivel_aluno,$id);

	}

	/**
	 * @return null
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getCaminho() {
		return $this->caminho;
	}

	/**
	 * @return boolean
	 */
	public function isVisivelAluno() {
		return $this->visivelAluno;
	}

	/**
	 * @return mixed
	 */
	public function getIdEstagio() {
		return $this->idEstagio;
	}

	/**
	 * @return string descricao do arquivo
	 */
	public function getDescricao() {
		return substr(strstr($this->caminho,'.'),1);
	}

	public function delete() {
		$sql = "DELETE FROM arquivo WHERE id=?";
		require_once ("ConexaoBD.php");
		$conexao = ConexaoBD::getConnection();
		if ($conexao->inTransaction()) {
			return null;
		}
		$conexao->beginTransaction();
		$statement = $conexao->prepare($sql);
		$success = $statement->execute(array($this->id));
		//	$success ? $conexao->commit() : $conexao->rollBack();
		if ($success) {
			$conexao->commit();
            unlink(ConfigClass::diretorioArquivos . '/' . $this->caminho);
		} else {
			$conexao->rollBack();
		}
		return $success;
	}



}