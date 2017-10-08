<?php $this->layout('esqueleto', ['title' => 'Gestor de programaciones']) ?>

<?php $this->start('header') ?>
	<h1>Programaciones didácticas IES Libertas</h1>
<?php $this->stop() ?>

<div id="error" style="display:<?= is_null($error)? 'none' : 'block'?>"><?=$error?></div>

<?php foreach($dptos as $dpto): ?>
 <div class='departamento' onclick="window.location='asignaturas.php?d=<?= $this->e($dpto->clave) ?>'">
    <a><?= $this->e($dpto->nombre) ?></a>
</div>
<?php endforeach ?>
