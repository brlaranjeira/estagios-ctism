<?php

/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 24/06/16
 * Time: 10:15
 */
class Anotacao {

    //private $tipo;


    private $id;
    private $texto;

    /**
     * @var Usuario
     */
    private $autor;

    private $dttime;

    /**
     * @var Arquivo
     */
    private $id_arquivo;

    /**
     * @var Estagio
     */
    private $id_estagio;

    public function __construct( $texto, $autor, $arquivo=null, $estagio=null ) {
        /* ESTAGIO / ANOTACAO */
        (!isset($estagio) && !isset($arquivo)) and die('um estagio (ou arquivo) precisa ser especificado.');
        if (!isset($estagio)) { //pega estagio pelo arquivo
            $arquivo = is_object($arquivo) ? $arquivo->getId() : $arquivo;
            $estagio = Estagio::getByArquivoId($arquivo);
            $estagio = $estagio->getId();
        }

        /* USUARIO AUTOR */
        !isset($autor) and die ('a anotacao precisa de um autor');
        $autor = (is_object($autor)) ? $autor : new Usuario($autor);

        /* DEFINICOES NORMAIS */
        $this->texto   = $texto;
        $this->id_arquivo = is_object($arquivo) ? $arquivo->getId() : $arquivo;
        $this->id_estagio = is_object($estagio) ? $estagio->getId() : $estagio;
        $this->autor   = $autor;
        //$this->dttime = date('Y-m-d H:i:s');
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param mixed $texto
     */
    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function setDttime($dttime) {
        $this->dttime = $dttime;
    }

    /**
     * @param Usuario $autor
     */
    public function setAutor($autor) {
        $this->autor = $autor;
    }

    /**
     * @param Arquivo $id_arquivo
     */
    public function setIdArquivo($id_arquivo) {
        $this->id_arquivo = $id_arquivo;
    }

    /**
     * @param Estagio $id_estagio
     */
    public function setIdEstagio($id_estagio) {
        $this->id_estagio = $id_estagio;
    }

    public static function getById($id) {
        $sql = 'SELECT texto, id_arquivo, id_estagio, autor , dttime FROM anotacao WHERE id=?';
        require_once ("ConexaoBD.php");
        $conexao = ConexaoBD::getConnection();
        $stmt = $conexao->prepare($sql);
        $stmt->execute(array($id));
        $result = $stmt->fetchObject();
        $ret = new Anotacao($result->texto,new Usuario($result->autor), $result->arquivo,$result->estagio);
        $ret->id = $id;
        $ret->dttime = $result->dttime;
        return $ret;
    }

    public function saveOrUpdate() {
        require_once ("ConexaoBD.php");
        $conexao = ConexaoBD::getConnection();
        if (isset($this->id)) { //update
            //update time
        } else {//save
            $sql = 'INSERT INTO anotacao ( texto , id_arquivo , id_estagio, autor , dttime ) values ( ? , ? , ? , ? , NOW() )';
            if ($conexao->inTransaction()) {
                return null;
            }
            $conexao->beginTransaction();
            $statement = $conexao->prepare($sql);
            $oarray = array( $this->texto , $this->id_arquivo, $this->id_estagio , $this->autor->getUid() );
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


    /**
     * @param $estagio Estagio|int estagio ou id do estagio
     *
     * @return Anotacao[]
     */
    public static function getAllForEstagio ( $estagio , $isAluno ) {
        $estagioId = is_object($estagio) ? $estagio->getId() : $estagio;
        $estagio = is_object($estagio) ? $estagio : Estagio::getById($estagio);
        $sql = 'SELECT A.id, A.texto, A.id_arquivo, A.autor , A.dttime FROM anotacao A LEFT JOIN arquivo AR ON A.id_arquivo = AR.id WHERE A.id_estagio = ? AND ( AR.id IS NULL OR ? OR AR.visivel_aluno <> ? ) ORDER BY A.dttime DESC';
        require_once ("ConexaoBD.php");
        $conexao = ConexaoBD::getConnection();
        $stmt = $conexao->prepare($sql);
        $oarray = array( $estagioId , !$isAluno , !$isAluno );
        $stmt->execute($oarray);
        $results = $stmt->fetchAll();
        $ret = array();
        foreach ( $results as $result ) {
            $currentAnotacao = new Anotacao( $result['texto'] , $result['autor'] , $result['id_arquivo'] , $estagioId );
            $currentAnotacao->id = $result['id'];
            $currentAnotacao->dttime = $result['dttime'];
            $ret[] = $currentAnotacao;
        }
        return $ret;
    }

    /**
     * @param $estagio Estagio|int estagio ou id do estagio
     */
    public static function getAllForArquivo ( $arquivo ) {
        $arquivoId = is_object($arquivo) ? $arquivo->getId() : $arquivo;
        //$estagio = is_object($estagio) ? $estagio : Estagio::getById($estagio);
        $sql = 'SELECT id, texto, id_estagio, autor , dttime FROM anotacao where id_arquivo = ? ORDER BY dttime DESC';
        require_once ("ConexaoBD.php");
        $conexao = ConexaoBD::getConnection();
        $stmt = $conexao->prepare($sql);
        $stmt->execute(array($arquivoId));
        $results = $stmt->fetchAll();
        $ret = array();
        foreach ( $results as $result ) {
            $currentAnotacao = new Anotacao($result['texto'],$result['autor'],$arquivoId,$result['id_estagio']);
            $currentAnotacao->id = $result['id'];
            $currentAnotacao->dttime = $result['dttime'];
            $ret[] = $currentAnotacao;
        }
        return $ret;
    }

    function __toString() {
        $ret = '{';
        $ret .= "\"id\":\"$this->id\",";
		$jsonText = json_encode($this->texto);
		$jsonText = str_replace('\n','<br/>',$jsonText);
        $ret .= "\"texto\":" . $jsonText . ",";
        $ret .= "\"autor\":$this->autor,";
        require_once ("Utils.php");
        $dtArray = explode(' ', $this->dttime);
        $brDate = Utils::USDateToBRDate($dtArray[0]);
        $time = $dtArray[1];
        $ret .= "\"dttime\":\"$brDate $time\",";
        $ret .= "\"id_arquivo\":\"$this->id_arquivo\",";
        $ret .= "\"id_estagio\":\"$this->id_estagio\"";
        $ret .= '}';
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
     * @return mixed
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @return Usuario
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * @return mixed
     */
    public function getDttime()
    {
        return $this->dttime;
    }

    /**
     * @return Arquivo
     */
    public function getIdArquivo()
    {
        return $this->id_arquivo;
    }

    /**
     * @return Estagio
     */
    public function getIdEstagio()
    {
        return $this->id_estagio;
    }
    
    

}