<?php

/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 25/04/2016
 * Time: 11:04
 */
class Usuario implements Serializable {


    /**
     * @var string id do usuario
     */
    private $uid;
    /**
     * @var string uidNumber do usuario
     */
    private $uidNumber;
    /**
     * @var array grupos a que o usuario pertence
     */
    private $grupos;
    /**
     * @var string nome completo
     */
    private $fullName;

    /**
     * Usuario constructor.
     * @param string $uid
     * @param bool $loadGrupos
     * @param string $fullName
     * @param string $uidNumber
     */
    public function __construct($uid, $loadGrupos = false, $fullName = null, $uidNumber = null ) {
    	$this->uid = $uid;
    	$this->fullName = $fullName;
        !isset($fullName) and $this->loadFullName();
        $this->uidNumber = $uidNumber;
        !isset($uidNumber) and $this->loadUidNumber();
        $loadGrupos and $this->loadGrupos();
    }

    
    
    /**
     *
     */
    public function loadGrupos() {
        $this->grupos = array();
        require_once("lib/LDAP/ldap.php");
        $ldap = new ldap();
        $allGroups = $ldap->getXbyY('gidNumber', 'cn', '*', LDAP_GROUPS_BASE);
        foreach ($allGroups as $gid) {
            if ($ldap->isMembroDoGrupo($this->uid, $gid)) {
                $this->grupos[] = $gid;
            }
        }
    }

    /**
     *
     */
    public function loadUidNumber() {
        require_once("lib/LDAP/ldap.php");
        $ldap = new ldap();
        $this->uidNumber = $ldap->getXbyY('uidnumber','uid',$this->uid);
    }

    /**
     *
     */
    public function loadFullName() {
        require_once("lib/LDAP/ldap.php");
        $ldap = new ldap();
        $this->fullName = $ldap->getXbyY('cn','uid',$this->uid);
    }

    /**
     * @param $gids
     *
     * @return Usuario[]
     */
    public static function getAllFromGroups($gids) {
        require_once ("lib/LDAP/ldap.php");
        $ldap = new ldap();

        $gids = is_array($gids) ? $gids : array($gids);

        $getByGid = array_map( function($usr) {
            return new Usuario($usr['uid'],false,$usr['cn'],$usr['uidNumber']);
        }, $ldap->getXbyY(array('uid','cn','uidNumber'),'gidnumber',$gids));


        $arr2 = $ldap->getXbyY('memberuid','gidnumber',$gids,LDAP_GROUPS_BASE);
        $getFromGroup = array_map(function($usr) {
            return new Usuario($usr,false);
        }, $arr2);



        $ret = array_merge($getByGid,$getFromGroup);

        /*$ret = array_map(function($usr) {
            return new Usuario($usr,false);
        }, $ldapMerge );*/
        usort( $ret, function( $usrx, $usry ) {
            return strcmp(strtolower($usrx->fullName),strtolower($usry->fullName));
        });
        $last = $ret[0];
        $rem = array();
        for ( $i = 1; $i < sizeof($ret); $i++ ) {
            if ($ret[$i]->uid== $last->uid) {
                $rem[] = $i;
            }
            $last = $ret[$i];
        }
        foreach ($rem as $i) {
            unset($ret[$i]);
        }
        return $ret;
    }

    /**
     * @param $groupId
     * @return bool
     */
    public function hasGroup($groupId) {
        return in_array($groupId,$this->grupos);
    }

    /**
     * @return mixed
     */
    public function getUid() {
        return $this->uid;
    }

    /**
     * @return null
     */
    public function getUidNumber() {
        return $this->uidNumber;
    }

    /**
     * @return mixed
     */
    public function getGrupos() {
        !isset($this->grupos) and $this->loadGrupos();
        return $this->grupos;
    }

    /**
     * @return null
     */
    public function getFullName() {
        return $this->fullName;
    }

    public function serialize() {
        $delim = ' ||| ';
        $str =  'uid:=' . serialize($this->uid) . $delim;
        $str .= 'uidNumber:=' . serialize($this->uidNumber) . $delim;
        $str .= 'grupos:=' . serialize($this->grupos) . $delim;
        $str .= 'fullName:=' . serialize($this->fullName);
        return $str;
    }

    public function unserialize($serialized) {
        $serialized = strstr($serialized,'{');
        $serialized = substr($serialized,1,strlen($serialized)-2);
        $delim = ' ||| ';
        $partes = explode($delim,$serialized);
        foreach ($partes as $parte) {
            list($k,$v) = explode(':=',$parte);
            $$k = unserialize($v);
        }
        $usr = new Usuario($uid,false,$fullName,$uidNumber);
        $usr->grupos = $grupos;
        return $usr;
    }

    function __toString() {
        $ret = '{';
        $ret .= '"uid":"' . $this->uid . '",';
        $ret .= '"uidNumber":"' . $this->uidNumber . '",';
        $ret .= '"grupos":[';
        $primeiro = true;
        foreach ( $this->getGrupos() as $grupo ) {
            $ret .= !$primeiro ? ',' : '';
            $primeiro = false;
            $ret .= '"' . $grupo . '"';
        }
        $ret .= '],';
        $ret .= '"fullName":"' . $this->fullName . '"';
        $ret .= '}';
        return $ret;
    }



}