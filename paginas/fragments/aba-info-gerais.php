<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 01/07/16
 * Time: 15:09
 */
?>
<form id="form-edita-estagio" method="post" action="novoestagio.php">
    <!--<div class="row">
        <div class="form-group col-xs-12"> -->
            <?
                /*$alunoUsr = new Usuario($estagio->getAluno());
                echo '<h4><strong>Aluno: </strong>' . $alunoUsr->getFullName() . '</h4>';*/
            ?>
<!--        </div>
    </div> -->
    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            <label for="idaluno">Aluno</label>
            <? if ( $usuario->getUid() != $estagio->getAluno() ) { echo '<div class="input-group">';}?>
                <select disabled class="form-control" id="idaluno" name="idaluno">
                    <?
                        require_once ("Grupos.php");
                        $alunos = Usuario::getAllFromGroups(Grupos::ALUNOS);
                        foreach ($alunos as $aluno) {
                            $selectedStr = $estagio->getAluno() == $aluno->getUid() ? 'selected' : '';
                            echo "<option $selectedStr value=\"{$aluno->getUid()}\">{$aluno->getFullName()}</option>";
                        }
                    ?>
                </select>
            <? if ( $usuario->getUid() != $estagio->getAluno() ) { ?>
                <span cod="idaluno" class="span-editar input-group-addon glyphicon glyphicon-edit"></span>
            <? } ?>
            <? if ( $usuario->getUid() != $estagio->getAluno() ) { echo '</div>';}?>
        </div>
        <div class="form-group col-md-6 col-xs-12">
            <label for="profresp">Professor Respons&aacute;vel</label>
            <? if ($usuario->getUid() != $estagio->getProfessor()) { echo '<div class="input-group">';}?>
            <select disabled class="form-control" id="profresp" name="profresp">
                <?
                require_once ("Grupos.php");
                $professores = Usuario::getAllFromGroups(Grupos::PROFESSORES);
                foreach ( $professores as $prof ) {
                    $selectedStr = ($estagio->getProfessor() == $prof->getUid()) ? ' selected ' : ' ';
                    echo '<option' . $selectedStr . 'value="' . $prof->getUid() . '">' . $prof->getFullName() . '</option>';
                }
                ?>
            </select>
            <? if ($usuario->getUid() != $estagio->getProfessor()) { ?>
                <span cod="profresp" class="span-editar input-group-addon glyphicon glyphicon-edit"></span>
            <? } ?>
            <? if ($usuario->getUid() != $estagio->getProfessor()) { echo '</div>';}?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            <label for="dtini">Data de In&iacute;cio</label>
            <? require_once ("Utils.php");?>
            <div class="input-group">
                <input disabled type="text" name="dtini" id="dtini" class="form-control input-date" value="<?=Utils::USDateToBRDate($estagio->getDtInicio())?>">
                <span cod="dtini" class="span-editar input-group-addon glyphicon glyphicon-edit"></span>
            </div>
        </div>
        <div class="form-group col-md-6 col-xs-12">
            <label for="dtfim">Data de Fim</label>
            <div class="input-group">
                <input disabled type="text" name="dtfim" id="dtfim" class="form-control input-date" value="<?=Utils::USDateToBRDate($estagio->getDtFim())?>">
                <span cod="dtfim" class="span-editar input-group-addon glyphicon glyphicon-edit"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-xs-12">
            <label for="idcurso">Curso</label>
            <div class="input-group">
                <select disabled class="form-control" id="idcurso" name="idcurso">
                    <?
                    require_once ("Curso.php");
                    $cursos = Curso::getAll();
                    foreach ( $cursos as $curso ) {
                        $selectedStr = ($curso->getId() == $estagio->getCurso()->getId()) ? ' selected ' : ' ';
                        echo '<option' . $selectedStr . 'value="' . $curso->getId() . '">' . $curso->getDescricao() . '</option>';
                    }
                    ?>
                </select>
                <span cod="idcurso" class="span-editar input-group-addon glyphicon glyphicon-edit"></span>
            </div>
        </div>
        <div class="form-group col-md-6 col-xs-12">
            <label for="obrigatorio">Obrigat&oacute;rio</label>
            <div class="input-group">
                <select disabled class="form-control" id="obrigatorio" name="obrigatorio">
                    <option value="on">Sim</option>
                    <option value="off">N&atilde;o</option>
                </select>
                <span cod="obrigatorio" class="span-editar input-group-addon glyphicon glyphicon-edit"></span>
            </div>
        </div>
    </div>


<div class="row">


    <div class="form-group col-xs-12">
        <label for="nota">Nota</label>
        <? if ($usuario->getUid() != $estagio->getAluno() && $estagio->getEstado()->getId() != Estado::EM_DESENV) { echo '<div class="input-group">';}?>
        <input disabled min="0" max="10" step="0.1" type="number" name="nota" id="nota" class="form-control" value="<? if ($estagio->getNota() != null) {echo $estagio->getNota();} ?>">
        <? if ($usuario->getUid() != $estagio->getAluno() && $estagio->getEstado()->getId() != Estado::EM_DESENV) { ?>
            <span cod="nota" class="span-editar input-group-addon glyphicon glyphicon-edit"></span>
        <? } ?>
        <? if ($usuario->getUid() != $estagio->getAluno() && $estagio->getEstado()->getId() != Estado::EM_DESENV) { echo '</div>';}?>
    </div>
</div>
    <? for ($i = 0; $i < sizeof($estagio->getEmpresas()); $i++) {
        $empresa = $estagio->getEmpresas()[$i];
        echo '<div class="row"><div class="well"><div class="input-group input-group-empresa" cod="'. $i .'">';
            echo '<input cod="nomeempresa" class="form-control input-empresa" type="text" disabled value="[' . $empresa->getCnpj(true) . '] ' . $empresa->getRazaoSocial() . '">';
            echo '<input cod="carga_horaria" name="c_h[]" type="text" disabled class="form-control" value="' . $empresa->getCargaHoraria() . '">';
            echo '<input type="hidden" name="empresa-info" class="form-control" cod="empresa-info" value="' . htmlentities($empresa->asJSON()) . '"">';
            echo '<input type="text" disabled name="supervisor[]" class="form-control" cod="supervisor" value="' . $empresa->getSupervisor() . '">';
            echo '<span class="span-editar span-edita-empresa input-group-addon glyphicon glyphicon-edit"></span>';
            echo '<span class="span-remove-empresa input-group-addon glyphicon glyphicon-remove"></span>';
            if ( $i == sizeof($estagio->getEmpresas()) - 1 ) {
                echo '<span class="span-nova-empresa input-group-addon glyphicon glyphicon-plus"></span>';
            }
        echo '</div></div></div>';
    }
    ?>
<div class="row">
    <div class="col-xs-12">
        <button id="btn-edita-estagio" type="button" class="btn btn-block btn-success">Salvar</button>
    </div>
</div>
</form>