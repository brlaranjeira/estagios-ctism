<?php

/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 27/07/16
 * Time: 12:29
 */
class ConfigClass {



    const notaMinima = 7.0;

    const diretorioArquivos = '/var/local/estagios'; //linux
	//const diretorioArquivos = 'F:/relatoriosestagio/arquivos'; //windows

    const diretorioCertificados = '/var/local/estagios/certificados'; //linux
    //const diretorioCertificados = 'F:/relatoriosestagio/certificados'; //windows

    const dbHost = 'dev'; //debug
	//const dbHost = 'bdctism'; //prod
    const dbUser = 'relestagio';
    const dbName = 'relatoriosestagio';
    const dbPasswd = ''; //debug
	//const dbPasswd = ''; //prod

    const mysqlCharset = 'utf8';

    const defaultMailAddr = 'estagios@ctism.ufsm.br';

    const sysName = 'Portal do Estágio';

}