<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 13/04/2016
 * Time: 17:20
 */
ini_set('default_charset','utf-8');
require_once ("Usuario.php");
require_once ("Paginas.php");
Paginas::forcaSeguranca();

?>
<div class="form-group col-md-6">
	<label for="profresp">Professor Respons&aacute;vel</label>
	<select class="form-control" name="profresp" id="profresp">
		<?
		require_once ("Grupos.php");
		$professores = Usuario::getAllFromGroups(Grupos::PROFESSORES);
		foreach ( $professores as $prof ) {
			echo '<option value="' . $prof->getUid() . '">' . $prof->getFullName() . '</option>';
		}
		?>
	</select>
</div>
<div class="form-group col-md-6">
	<label for="relfile">Arquivo</label>
	<input type="file" name="relfile" id="relfile" class="form-control">
</div>
<div class="form-group col-md-12">
	<button id="btn-enviar" type="button" class="btn btn-primary btn-block">Enviar</button>
</div>

<script type="application/javascript" language="javascript" src="./js/enviar.js"></script>