<?php

/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 25/05/2016
 * Time: 13:51
 */
class Utils {

	static function USDateToBRDate ( $usdate ) {
		$partes = explode('-',$usdate);
		return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
	}

	static function BRDateToUSDate ( $brdate ) {
		$partes = explode('/',$brdate);
		return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
	}

	static function debugSQL ( $sql , $params ) {
	    while (sizeof($params) > 0) {
	        $pos = strpos($sql,'?');
            $sql = substr_replace($sql,$params[0],$pos,1);
            array_shift($params);
        }
        return $sql;
    }

    static function sendMail( $to, $subject, $text, $retries = 1 , $from) {
        require_once ("ConfigClass.php");
        $from = isset($from) ? $from : ConfigClass::defaultMailAddr;
        for ($i=0 ; $i < $retries; $i++) {
            $sent = mail($to, $subject, $text, 'From: ' . $from);
            if ($sent) {
                return true;
            }
        }
        return false;
    }

    static function mask($mask,$str){
        $str = str_replace(" ","",$str);
        for($i=0;$i<strlen($str);$i++){
            $mask[strpos($mask,"#")] = $str[$i];
        }
        return $mask;
    }

    static function getMIMEType ( $extension ) {
		$mapa = array (
			"aac" => "audio/aac" ,
			"abw" => "application/x-abiword" ,
			"arc" => "application/octet-stream" ,
			"avi" => "video/x-msvideo" ,
			"azw" => "application/vnd.amazon.ebook" ,
			"bin" => "application/octet-stream" ,
			"bz" => "application/x-bzip" ,
			"bz2" => "application/x-bzip2" ,
			"csh" => "application/x-csh" ,
			"css" => "text/css" ,
			"csv" => "text/csv" ,
			"doc" => "application/msword" ,
			"epub" => "application/epub+zip" ,
			"gif" => "image/gif" ,
			"htm" => "text/html" ,
			"html" => "text/html" ,
			"ico" => "image/x-icon" ,
			"ics" => "text/calendar" ,
			"jar" => "application/java-archive" ,
			"jpeg" => "image/jpeg" ,
			"jpg" => "image/jpeg" ,
			"js" => "application/javascript" ,
			"json" => "application/json" ,
			"mid" => "audio/midi" ,
			"midi" => "audio/midi" ,
			"mpeg" => "video/mpeg" ,
			"mpkg" => "application/vnd.apple.installer+xml" ,
			"odp" => "application/vnd.oasis.opendocument.presentation" ,
			"ods" => "application/vnd.oasis.opendocument.spreadsheet" ,
			"odt" => "application/vnd.oasis.opendocument.text" ,
			"oga" => "audio/ogg" ,
			"ogv" => "video/ogg" ,
			"ogx" => "application/ogg" ,
			"pdf" => "application/pdf" ,
			"ppt" => "application/vnd.ms-powerpoint" ,
			"rar" => "application/x-rar-compressed" ,
			"rtf" => "application/rtf" ,
			"sh" => "application/x-sh" ,
			"svg" => "image/svg+xml" ,
			"swf" => "application/x-shockwave-flash" ,
			"tar" => "application/x-tar" ,
			"tif" => "image/tiff" ,
			"tiff" => "image/tiff" ,
			"ttf" => "font/ttf" ,
			"vsd" => "application/vnd.visio" ,
			"wav" => "audio/x-wav" ,
			"weba" => "audio/webm" ,
			"webm" => "video/webm" ,
			"webp" => "image/webp" ,
			"woff" => "font/woff" ,
			"woff2" => "font/woff2" ,
			"xhtml" => "application/xhtml+xml" ,
			"xls" => "application/vnd.ms-excel" ,
			"xml" => "application/xml" ,
			"xul" => "application/vnd.mozilla.xul+xml" ,
			"zip" => "application/zip" ,
			"3gp" => "video/3gpp" ,
			"3g2" => "video/3gpp2" ,
			"7z" => "application/x-7z-compressed"
		);
		return array_key_exists($extension,$mapa) ? $mapa[$extension] : 'application/octet-stream';
	}
}