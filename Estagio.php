<?php

/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 25/05/2016
 * Time: 10:20
 */
class Estagio {

	private $id;
	private $aluno;
	private $professor;
	private $nota;
	private $dt_inicio;
	private $dt_fim;
	private $obrigatorio;
	/*private $carga_horaria;
	private $token;
    private $supervisor;*/

	/**
	 * @type Estado
	 */
	private $estado;
    /**
     * @type Estado
     */
    private $estado_anterior;
	/**
	 * @type Curso
	 */
	private $curso;
	private $local;

    /**
     * @type Empresa[]
     */
    private $empresas;

	/**
	 * Estagio constructor.
	 *
	 * @param $aluno
	 * @param $professor
	 * @param $nota
	 * @param $dt_inicio
	 * @param $dt_fim
	 * @param $estado
	 * @param $curso
	 * @param $local
	 */
	public function __construct( $id, $aluno, $professor, $nota, $dt_inicio, $dt_fim, $estado, $curso, $obrigatorio, $empresas=array(), $estado_anterior = null) {

		$this->aluno = $aluno;
		$this->professor = $professor;
		$this->nota = $nota;
		$this->dt_inicio = $dt_inicio;
		$this->dt_fim = $dt_fim;
		$this->obrigatorio = $obrigatorio;
		/*$this->carga_horaria = $carga_horaria;
		$this->local = $local;
        $this->supervisor = $supervisor;*/
        foreach ( $empresas as $empresa ) {
            $this->empresas[] = is_object($empresa) ? $empresa : Empresa::getById($empresa);
        }
		if (isset($id)) {
			$this->id = $id;
		}
		/*if (isset($token)) {
			$this->token = $token;
		}*/
		if (!is_object($estado)) {
			require_once ("Estado.php");
			$this->estado = Estado::getById($estado);
		} else {
			$this->estado = $estado;
		}
		if (isset($estado_anterior)) {
		    if (!is_object($estado_anterior)) {
		        require_once ("Estado.php");
                $this->estado_anterior = Estado::getById($estado_anterior);
            } else {
                $this->estado_anterior = $estado_anterior;
            }
        }
		if (!is_object($curso)) {
			require_once ("Curso.php");
			$this->curso = Curso::getById($curso);
		} else {
			$this->curso = $curso;
		}


	}

	/**
     * @param $id
     *
	 * @return Estagio
	 */
	public static function getById( $id ) {
        require_once ("ConexaoBD.php");
        require_once ("Empresa.php");
        $sql = "SELECT e.aluno, e.professor, e.nota, e.data_inicio, e.data_fim, e.id_estado, e.id_curso, e.obrigatorio, e.id_estado_anterior, ee.id_empresa, ee.carga_horaria, ee.supervisor FROM estagio e LEFT JOIN estagio_tem_empresa ee ON e.id = ee.id_estagio WHERE e.id = ?";
		$statement = ConexaoBD::getConnection()->prepare($sql);
		$statement->execute(array($id));
		$results = $statement->fetchAll();
        $ret = $results[0];
        $estagio = new Estagio($id, $ret['aluno'], $ret['professor'], $ret['nota'], $ret['data_inicio'], $ret['data_fim'],
            $ret['id_estado'],$ret['id_curso'], $ret['obrigatorio'], array() ,$ret['id_estado_anterior']);
        $empresas = array();
		foreach ( $results as $result ) {
		    $emp = Empresa::getById($result['id_empresa']);
            $emp->setSupervisor($result['supervisor']);
            $emp->setCargaHoraria($result['carga_horaria']);
            $empresas[] = $emp;
		}
		$estagio->setEmpresas($empresas);
        return $estagio;
    }

	/**
	 * @param $aluno Usuario
	 *
	 * @return Estagio[]
	 */
	public static function getAllFromAluno( $aluno ) {
        require_once ("ConexaoBD.php");
        require_once ("Empresa.php");
        $sql = "SELECT e.id, e.professor, e.nota, e.data_inicio, e.data_fim, e.id_estado, e.id_curso, e.obrigatorio, e.id_estado_anterior, ee.id_empresa, ee.carga_horaria, ee.supervisor FROM estagio e LEFT JOIN estagio_tem_empresa ee ON e.id = ee.id_estagio WHERE e.aluno = ?";
		$statement = ConexaoBD::getConnection()->prepare($sql);
		$statement->execute(array($aluno->getUid()));
		$results = $statement->fetchAll();
		$ret = array();
		foreach ( $results as $result ) {
            $emp = Empresa::getById($result['id_empresa']);
            $emp->setSupervisor($result['supervisor']);
            $emp->setCargaHoraria($result['carga_horaria']);
		    if (!isset($lastId) || $lastId != $result['id']) {
                if (!empty($ret)) {
                    $ret[sizeof($ret)-1]->setEmpresas(isset($empresas)?$empresas:array());
                }
                $ret[] = new Estagio($result['id'],$aluno->getUid(),$result['professor'],$result['nota'],$result['data_inicio'],$result['data_fim'],$result['id_estado'],$result['id_curso'],$result['obrigatorio'],array(),$result['id_estado_anterior']);
                $empresas = array($emp);
            } else {
                $empresas[] = $emp;
            }
            $lastId = $result['id'];
		}
        $ret[sizeof($ret)-1]->setEmpresas(isset($empresas)?$empresas:array());
		return $ret;
	}

	/**
	 * @param $estado Estado|int
	 *
	 * @return Estagio[]
	 */
	public static function getAllByEstado( $estado ) {
        require_once ("ConexaoBD.php");
        require_once ("Empresa.php");
        require_once ("Estado.php");
		$estado = is_object($estado) ? $estado->getId() : $estado;
        $sql = "SELECT e.id, e.aluno, e.professor, e.nota, e.data_inicio, e.data_fim, e.id_curso, e.obrigatorio, e.id_estado_anterior, ee.id_empresa, ee.carga_horaria, ee.supervisor FROM estagio e LEFT JOIN estagio_tem_empresa ee ON e.id = ee.id_estagio WHERE e.id_estado = ?";
        $statement = ConexaoBD::getConnection()->prepare($sql);
        $statement->execute(array($estado));
        $results = $statement->fetchAll();
        $ret = array();
        foreach ( $results as $result ) {
            $emp = Empresa::getById($result['id_empresa']);
            $emp->setSupervisor($result['supervisor']);
            $emp->setCargaHoraria($result['carga_horaria']);
            if (!isset($lastId) || $lastId != $result['id']) {
                if (!empty($ret)) {
                    $ret[sizeof($ret)-1]->setEmpresas(isset($empresas)?$empresas:array());
                }
                $ret[] = new Estagio($result['id'],$result['aluno'],$result['professor'],$result['nota'],$result['data_inicio'],$result['data_fim'],$estado,$result['id_curso'],$result['obrigatorio'],array(),$result['id_estado_anterior']);
                $empresas = array($emp);
            } else {
                $empresas[] = $emp;
            }
            $lastId = $result['id'];
        }
        $ret[sizeof($ret)-1]->setEmpresas(isset($empresas)?$empresas:array());
        return $ret;
	}

	/**
	 * @param $professor Usuario
	 * @return Estagio[]
	 */
	public static function getAllFromProfessor( $professor ) {
        require_once ("ConexaoBD.php");
        require_once ("Empresa.php");
        $sql = "SELECT e.id, e.aluno, e.nota, e.data_inicio, e.data_fim, e.id_estado, e.id_curso, e.obrigatorio, e.id_estado_anterior, ee.id_empresa, ee.carga_horaria, ee.supervisor FROM estagio e LEFT JOIN estagio_tem_empresa ee ON e.id = ee.id_estagio WHERE e.professor = ?";
        $statement = ConexaoBD::getConnection()->prepare($sql);
        $statement->execute(array($professor->getUid()));
        $results = $statement->fetchAll();
        $ret = array();
        foreach ( $results as $result ) {
            $emp = Empresa::getById($result['id_empresa']);
            $emp->setSupervisor($result['supervisor']);
            $emp->setCargaHoraria($result['carga_horaria']);
            if (!isset($lastId) || $lastId != $result['id']) {
                if (!empty($ret)) {
                    $ret[sizeof($ret)-1]->setEmpresas(isset($empresas)?$empresas:array());
                }
                $ret[] = new Estagio($result['id'],$result['aluno'],$professor->getUid(),$result['nota'],$result['data_inicio'],$result['data_fim'],$result['id_estado'],$result['id_curso'],$result['obrigatorio'],array(),$result['id_estado_anterior']);
                $empresas = array($emp);
            } else {
                $empresas[] = $emp;
            }
            $lastId = $result['id'];
        }
        $ret[sizeof($ret)-1]->setEmpresas(isset($empresas)?$empresas:array());
        return $ret;
	}

	/**
	 * @param $arqId
	 * @return Estagio
	 */
	public static function getByArquivoId($arqId) {
		$sql = "SELECT a.id, e.id , e.aluno, e.professor, e.nota, e.data_inicio, e.data_fim, e.id_estado_anterior, e.id_estado, e.id_curso, e.local_estagio, e.supervisor FROM estagio e LEFT JOIN arquivo a on a.id_estagio=e.id WHERE a.id=?";
		require_once ("ConexaoBD.php");
		$statement = ConexaoBD::getConnection()->prepare($sql);
		$statement->execute(array($arqId));
		$ret = $statement->fetchObject();
		require_once ("Utils.php");
		return new Estagio($ret->id,$ret->aluno,$ret->professor,$ret->nota,$ret->data_inicio,Utils::USDateToBRDate($ret->data_fim),$ret->id_estado,$ret->id_curso,$ret->local_estagio,$ret->obrigatorio,$ret->carga_horaria,$ret->token,$ret->supervisor,$ret->id_estado_anterior);
	}

    /**
     * @param $criterion
     * @param $sort
     * @return Estagio[]
     */
    public static function getAll($criterion, $sort, $filter) {
        require_once ("ConexaoBD.php");
        require_once ("Empresa.php");
        $sql = "SELECT e.id, e.aluno, e.professor, e.nota, e.data_inicio, e.data_fim, e.id_estado, e.id_curso, e.obrigatorio, e.id_estado_anterior, ee.id_empresa, ee.carga_horaria, ee.supervisor FROM estagio e LEFT JOIN estagio_tem_empresa ee ON e.id = ee.id_estagio  LEFT JOIN empresa em ON em.id = ee.id_empresa $filter ORDER BY ? " . (strtolower($sort) == 'desc' ? 'DESC' : 'ASC');
        $statement = ConexaoBD::getConnection()->prepare($sql);
        $statement->execute(array($criterion));
        $results = $statement->fetchAll();
        $ret = array();
        foreach ( $results as $result ) {
            $emp = Empresa::getById($result['id_empresa']);
            $emp->setSupervisor($result['supervisor']);
            $emp->setCargaHoraria($result['carga_horaria']);
            if (!isset($lastId) || $lastId != $result['id']) {
                if (!empty($ret)) {
                    $ret[sizeof($ret)-1]->setEmpresas(isset($empresas)?$empresas:array());
                }
                $ret[] = new Estagio($result['id'],$result['aluno'],$result['professor'],$result['nota'],$result['data_inicio'],$result['data_fim'],$result['id_estado'],$result['id_curso'],$result['obrigatorio'],array(),$result['id_estado_anterior']);
                $empresas = array($emp);
            } else {
                $empresas[] = $emp;
            }
            $lastId = $result['id'];
        }
        $ret[sizeof($ret)-1]->setEmpresas(isset($empresas)?$empresas:array());
        return $ret;
    }

    public function save() {
		require_once ("ConexaoBD.php");
        require_once ("Utils.php");
		$conexao = ConexaoBD::getConnection();
        if ($conexao->inTransaction()) {
            return null;
        }
		if (isset($this->id)) {//update
			$sql = 'UPDATE estagio SET aluno=?, professor=?, nota=?, data_inicio=?, data_fim=?, id_estado=?, id_curso=?, obrigatorio=b? WHERE id=?';
			$conexao->beginTransaction();
			$statement = $conexao->prepare($sql);
            $nota = $this->nota; $estado = $this->estado->getId(); $curso = $this->curso->getId();
            $obrigatorio = $this->obrigatorio ? 1 : 0; $ch = $this->carga_horaria; $token = str_replace('.','',$this->token);

			$oarray = array($this->aluno,$this->professor,$nota,$this->dt_inicio,$this->dt_fim,$estado,$curso,$obrigatorio,$this->id);

			$execOk = $statement->execute($oarray);
            if (!$execOk) {
                $conexao->rollBack();
                return null;
            }
			$sql = 'DELETE FROM estagio_tem_empresa WHERE id_estagio = ?';
			$statement = $conexao->prepare($sql);
			$oarray = array($this->id);
			$execOk = $statement->execute($oarray);
			if (!$execOk) {
				$conexao->rollBack();
				return null;
			}
            foreach ( $this->empresas as $empresa ) {
				$sql = 'INSERT INTO estagio_tem_empresa (id_empresa, id_estagio, carga_horaria, supervisor) VALUES ( ? , ? , ? , ? )';
				$oarray = array($empresa->getId(), $this->id, $empresa->getCargaHoraria(), $empresa->getSupervisor());
				$statement = $conexao->prepare($sql);
				$execOk = $statement->execute($oarray);
				if (!$execOk) {
					$conexao->rollBack();
					return null;
				}
            }
            if ($conexao->commit()) {
                return $this->id;
            }

		} else { //insert
			$sql = 'INSERT INTO estagio (aluno,professor,nota,data_inicio,data_fim,id_estado,id_curso, obrigatorio) values ( ? , ? , ? , ? , ? , ? , ? , b? )';
			$conexao->beginTransaction();
			$statement = $conexao->prepare($sql);
			$oarray = array( $this->aluno, $this->professor, $this->nota, $this->dt_inicio, $this->dt_fim,
                $this->estado->getId(), $this->curso->getId(), $this->obrigatorio );
			$execOk = $statement->execute($oarray);
            if ( !$execOk ) {
                $conexao->rollBack();
                return null;
            }
			$idInserido = $conexao->lastInsertId('id');

            foreach ( $this->empresas as $empresa ) {
                $sql = 'INSERT INTO estagio_tem_empresa (id_empresa, id_estagio, carga_horaria, supervisor) VALUES ( ? , ? , ? , ? )';
                $oarray = array($empresa->getId(), $idInserido, $empresa->getCargaHoraria(), $empresa->getSupervisor());
                $statement = $conexao->prepare($sql);
                $execOk = $statement->execute($oarray);
                if (!$execOk) {
                    $conexao->rollBack();
                    return null;
                }
            }
			if ($conexao->commit()) {
				$this->id = $idInserido;
				return $this->id;
			}
			$conexao->rollBack();
			return null;
		}
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
	public function getAluno() {
		return $this->aluno;
	}

	/**
	 * @return mixed
	 */
	public function getProfessor() {
		return $this->professor;
	}

	/**
	 * @return mixed
	 */
	public function getNota() {
		return $this->nota;
	}

	/**
	 * @return mixed
	 */
	public function getDtInicio() {
		return $this->dt_inicio;
	}

	/**
	 * @return mixed
	 */
	public function getDtFim() {
		return $this->dt_fim;
	}

	/**
	 * @return Estado
	 */
	public function getEstado() {
		return $this->estado;
	}

	public function getEmpresas() {
	    return $this->empresas;
    }

	/**
	 * @param $estado Estado|int
	 */
	public function setEstado($estado) {
		if (is_object($estado)) {
			$this->estado = $estado;
		} else {
			$this->estado = Estado::getById($estado);
		}

	}

	/**
	 * @param mixed $aluno
	 */
	public function setAluno($aluno) {
		$this->aluno = $aluno;
	}

	/**
	 * @param mixed $professor
	 */
	public function setProfessor($professor) {
		$this->professor = $professor;
	}

	/**
	 * @param mixed $nota
	 */
	public function setNota($nota) {
		$this->nota = $nota;
	}

	/**
	 * @param mixed $dt_inicio
	 */
	public function setDtInicio($dt_inicio, $keepFormat = false) {
		require_once ("Utils.php");
		$dt_inicio = $keepFormat ? $dt_inicio : Utils::BRDateToUSDate($dt_inicio);
		$this->dt_inicio = $dt_inicio;
	}

	/**
	 * @param mixed $dt_fim
	 */
	public function setDtFim($dt_fim, $keepFormat = false) {
		require_once ("Utils.php");
		$dt_fim = $keepFormat ? $dt_fim : Utils::BRDateToUSDate($dt_fim);
		$this->dt_fim = $dt_fim;
	}

	/**
	 * @param Curso|int $curso curso ou id do curso
	 */
	public function setCurso($curso) {
		$this->curso = (is_object($curso)) ? $curso : Curso::getById($curso);
	}

	/**
	 * @return mixed
	 */
	public function isObrigatorio() {
		return $this->obrigatorio;
	}

	/**
	 * @param mixed $obrigatorio
	 */
	public function setObrigatorio($obrigatorio) {
		$this->obrigatorio = $obrigatorio;
	}


	/*
    public function generateToken() {
        $tmpToken = $this->aluno . $this->id . $this->professor;
        $tmpToken = md5( sha1( $tmpToken ) );
        $tmpToken = strtoupper( strrev( $tmpToken ) );
        return $tmpToken;
    }
	*/

    /*
    public function generateCertPDF ( $token ) {
        $reportName = '../jasper/EstagioCert.jrxml';
        require_once ('../Arquivo.php');
        require_once ('../ConfigClass.php');
        $fname = ConfigClass::diretorioCertificados . '/' . $this->getId() . '.pdf';
        $params = array();
        $aluno = new Usuario($this->getAluno());
        $professor = new Usuario($this->getProfessor());
        $params['aluno'] = $aluno->getFullName();
        $params['professor'] = $professor->getFullName();
        $params['tipo_estagio'] = $this->isObrigatorio() ? 'obrigatório' : 'optativo';
        $params['carga_horaria'] = $this->getCargaHoraria();
        $params['local'] = $this->getLocal();
        $params['token'] = chunk_split($token,4,'.');
        $params['token'] = substr($params['token'],0,strlen($params['token'])-1);
        $params['supervisor'] = $this->supervisor;

        $execParams = $reportName . ' ' . $fname;
        foreach ( $params as $k => $v ) {
            $execParams .= ' -' . $k . ' ' . str_replace(' ', '\ ', $v);
        }
        $retArray = array();
        try {
            $execRet = exec ('java -jar ../jasper/certificado-jasper.jar ' . $execParams, $retArray, $retVar);
            return $retArray[0];
        }catch (Exception $e) {
            return false;
        }
    }
    */

	/**
	 * @return Curso
	 */
	public function getCurso() {
		return $this->curso;
	}

	function asJSON( $isAluno ) {
		require_once ("Utils.php");

	    $ret = '{';

		$ret .= "\"id\": \"$this->id\",";
		$usuarioAluno = new Usuario($this->aluno);
		$usuarioProfessor = new Usuario($this->professor);
		$ret .= "\"aluno\": $usuarioAluno,";
		$ret .= "\"professor\": $usuarioProfessor,";
		$ret .= "\"nota\": \"$this->nota\",";
        $dtIni = Utils::USDateToBRDate($this->dt_inicio);
        $dtFim = Utils::USDateToBRDate($this->dt_fim);
		$ret .= "\"dt_inicio\": \"$dtIni\",";
		$ret .= "\"dt_fim\": \"$dtFim\",";
		$ret .= "\"estado\": $this->estado,";
		if (isset($this->estado_anterior)) {
			$ret .= "\"estado_anterior\": $this->estado_anterior,";
		}
		$ret .= "\"curso\": $this->curso,";
		$ret .= "\"local\": \"$this->local\",";
		$obrig = $this->obrigatorio ? '1' : '0';
		$ret .= "\"obrigatorio\": \"$obrig\",";
		/*$ret .= "\"carga_horaria\": \"$this->carga_horaria\",";
		$ret .= "\"token\": \"$this->token\",";
        $ret .= "\"supervisor\": \"$this->supervisor\",";*/
		require_once ("Anotacao.php");
		$anotacoes = Anotacao::getAllForEstagio( $this->id, $isAluno );
		$ret .= "\"anotacoes\":[";
		$first = true;
		foreach ( $anotacoes as $anotacao ) {
			$ret .= !$first ? ',' : '';
			$first = false;
			$ret .= $anotacao;
		}
        $ret .= '], "empresas":[';
        $first = true;
		foreach ( $this->empresas as $empresa ) {
            $ret .= !$first ? ',' : '';
            $first = false;
            $ret .= $empresa;
        }
		$ret .= "]";
		$ret .= '}';
		return $ret;
	}

	/*public static function getByToken( $token ) {
        $sql = "SELECT id, aluno, professor, nota, data_inicio, data_fim, id_estado_anterior, id_estado, id_curso, local_estagio, obrigatorio, carga_horaria, supervisor FROM estagio WHERE token = ?";
        require_once ("ConexaoBD.php");
        $statement = ConexaoBD::getConnection()->prepare($sql);
        $statement->execute(array($token));
        $ret = $statement->fetchObject();
        require_once ("Utils.php");
        return new Estagio($ret->id,$ret->aluno,$ret->professor,$ret->nota,Utils::USDateToBRDate($ret->data_inicio),Utils::USDateToBRDate($ret->data_fim),$ret->id_estado,$ret->id_curso,$ret->local_estagio,$ret->obrigatorio,$ret->carga_horaria,$token,$ret->supervisor,$ret->id_estado_anterior);
    }*/

    public function getCertificadoPath() {
        require_once ("Arquivo.php");
        require_once ("ConfigClass.php");
        return ConfigClass::diretorioCertificados . '/' . $this->id . '.pdf';
    }

    public function isNotaOk() {
        require_once ("ConfigClass.php");
        return $this->nota >= ConfigClass::notaMinima;
    }

    /**
     * @return mixed
     */
    public function getObrigatorio() {
        return $this->obrigatorio;
    }

    /**
     * @return Estado
     */
    public function getEstadoAnterior() {
        return $this->estado_anterior;
    }

    public function setEmpresas($empresas) {
        $this->empresas = $empresas;
    }


}