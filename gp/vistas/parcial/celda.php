<div class="divFichero">
    <a class="archivo" href="<?= $this->e($doc->href()) ?>"><?= $this->e($doc->nombre()) ?></a>
    <i class="fa fa-times" aria-hidden="true" onclick="destruye('<?= $doc->id() ?>', '<?= $doc->nombre() ?>');"></i>
</div>
<div class="divNoFichero">
    <p><?= join(', ', $doc->lista_extensiones()) ?></p>
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
</div>
<div id="loading">
    <div class="loader"></div>
</div>
<div class="dropArea" 
    mi_dpto="<?= $doc->dpto ?>" 
    mi_clave_asig="<?= $doc->clave_asig ?>" 
    mi_tipo_doc="<?= $doc->tipo_doc ?>" 
    mi_extensiones_id="<?= $doc->extensiones_id ?>" 
    mi_nivel="<?= $doc->nivel ?>">        
</div>

