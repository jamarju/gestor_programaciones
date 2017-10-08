<?php $this->layout('esqueleto', ['title' => 'Departamento de ' . $this->e($dpto->nombre)]) ?>

<?php $this->start('header') ?>
<h1>Departamento <?= $this->e($dpto->nombre) ?></h1>
<?php $this->stop() ?>

<?php if (isset($error)): ?>
<div id="error">"><?= $error ?></div>
<?php endif ?>

<form enctype="multipart/form-data" id="upload" style="display: none;">
    <input type="hidden" id="basename">
    <input type="file" name="file" id="file">
</form>

<?php foreach($dpto->asignaturas as $asignatura): ?>
<table class="asignatura genericos">
	<thead class="cabeceras">
		<tr>
		    <th colspan="5"><?= $this->e($asignatura->nombre) ?></th>
        </tr>
        <tr>
			<td colspan="2">General</td>
			<td>Memoria 1EV</td>
			<td>Memoria 2EV</td>
			<td>Memoria Final</td>
		</tr>
	</thead>
	<tbody>
		<tr>
            <?php foreach($asignatura->documentos as $doc) : ?>
                <td id="<?= $doc->id() ?>" class="<?= $doc->existe() ? 'existe' : 'noExiste' ?>">
                    <?php $this->insert('parcial/celda', [ 'doc' => $doc ]) ?>
                </td>
            <?php endforeach ?>
        </tr>
	</tbody>
</table>
<table class="asignatura">
	<thead class="cabeceras">
		<tr>
			<td class="nivel">Nivel</td>
			<td colspan="2">Programación</td>
			<td colspan="2">Resumen</td>
		</tr>
	</thead>
	<tbody>
     <?php foreach($asignatura->niveles as $nivel): ?>
     	<tr class="cursos">
			<td class="nivel"><?= $nivel->clave ?></td>
            <?php foreach($nivel->documentos as $doc): ?>
                <td id="<?= $doc->id() ?>" class="<?= $doc->existe() ? 'existe' : 'noExiste' ?>">
                    <?php $this->insert('parcial/celda', ['doc' => $doc]) ?>
                </td>
            <?php endforeach ?>
        </tr>
     <?php endforeach ?>
    </tbody>
</table>
<br>
<?php endforeach ?>
