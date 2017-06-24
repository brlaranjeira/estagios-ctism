<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 30/05/2016
 * Time: 17:07
 */
ini_set('default_charset','utf-8');
session_start();
require_once ("../Usuario.php");
require_once ("../Estagio.php");
require_once ("../Grupos.php");
$usuario = Usuario::unserialize($_SESSION['ctism_user']);

$estagio = Estagio::getById($_REQUEST['idestagio']);
if ( $usuario->getUid() != $estagio->getAluno() && $usuario->getUid() != $estagio->getProfessor() && !$usuario->hasGroup(Grupos::SSI) && !$usuario->hasGroup(Grupos::DREC_SECRETARIA) ) {
	http_response_code(403);
	die('Você não pode acessar esta página.');
}

require_once ("../Arquivo.php");
require_once ("../Grupos.php");
$getInvisiveis = $estagio->getProfessor() == $usuario->getUid() || $usuario->hasGroup(Grupos::DREC_SECRETARIA) || $usuario->hasGroup(Grupos::SSI);
$arquivos = Arquivo::getAllForEstagio($_REQUEST['idestagio'], !$getInvisiveis);
foreach ( $arquivos as $arquivo ) {
	echo '<tr>';

	/* DESCRICAO */
	echo '<td>' . $arquivo->getDescricao();
	if (!$arquivo->isVisivelAluno()) {
		echo ' (Arquivo invisível para o aluno)';
	}
	echo'</td>';

	/* ANOTACOES */
	echo '<td class="td-btn">';
	require_once ("../Anotacao.php");
	$anotacoes = Anotacao::getAllForArquivo($arquivo->getId());
	echo '<button type="button" class="btn btn-xs btn-primary btn-ver-anotacoes" arquivo="' . $arquivo->getId() . '">';

	$anotacoesJson = '[';
	$first = true;
	foreach ($anotacoes as $anotacao) {
		$anotacoesJson .= $first ? ' ' : ',';
		$first = false;
		$anotacoesJson .= $anotacao;
	}//docs.google.com/uc?id=0B4HusEKrlZiQbUlvd0kySHZKSXM&export=download
	$anotacoesJson .= ']';
	?>
		<script language="javascript" type="application/javascript">
			$('button[arquivo=<?=$arquivo->getId()?>]').attr('anotacoes','<?=$anotacoesJson?>');
			$('button[arquivo=<?=$arquivo->getId()?>]').click( function () {
				var anotacoes = JSON.parse($( this ).attr('anotacoes'));
				showMessage( getHTMLAnotacoes( anotacoes , false ) );
				/*
				var txt = anotacoes.length > 0 ? '' : 'Nenhuma anotação para este estágio.';
				var lastDate = '';
				for  (var i = 0; i < anotacoes.length; i++ ) {
					var anotacao = anotacoes[i];
					var dtHr = anotacao.dttime.split(' ');
					if (dtHr[0] != lastDate) {
						txt += '<span class="span-msgdata">'+dtHr[0] + '</span>';
					}
					txt += '<span class="span-msginfo">' + dtHr[1] + ' - ' + anotacao.autor.fullName + ': </span>';
					if (anotacao.id_arquivo.length) {
						txt += '<span class="span-msgfile">Anotação sobre o arquivo <a class="fileanchor" cod="' + anotacao.id_arquivo + '" href="./paginas/getfile.php?codarq=' + anotacao.id_arquivo + '"></a></span>'
					}
					txt += '<span class="span-msgbody">' + anotacao.texto + '</span>';
					lastDate = dtHr[0];
				}
				showMessage( txt );
				*/

			} );
		</script>
	<?
	echo '<span class="glyphicon glyphicon-envelope"></span>&nbsp;Abrir Anota&ccedil;&otilde;es (' . sizeof($anotacoes) . ')</button></td>';

	/* SALVAR */
	echo '<td class="td-btn"><a href="./paginas/getfile.php?codarq='.$arquivo->getId().'"><span cod="' . $arquivo->getId() . '" class="glyphicon glyphicon-floppy-save span-baixar-arquivo"></span></a></td>';

	/* EXCLUIR */
	if ($usuario->getUid() == $estagio->getAluno()) {
		echo '<td class="td-btn"><span cod="' . $arquivo->getId() . '" class="glyphicon glyphicon-remove span-deletar-arquivo span-red"></span></td>';
	}

	/* NOVA ANOTACAO */
	//if ($usuario->getUid() == $estagio->getProfessor()) {
	echo '<td class="td-btn"><span cod="' . $arquivo->getId() . '" class="glyphicon glyphicon-comment span-nova-anotacao-arquivo span-blue"></span></td>';
	//}

	echo '</tr>';
}

