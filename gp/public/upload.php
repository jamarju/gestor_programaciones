<?php
require_once '../vendor/autoload.php';
include_once('../application/config.inc.php');
require_once '../application/modelos.php';

// Este controlador se debe llamar con AJAX y devuelve un JSON con la respuesta ('ok' o 'error')
header('Content-type: application/json');

$templates = new League\Plates\Engine('../vistas');

$uploaded_name = $_FILES['file']['name'];
$uploaded_tmp_name = $_FILES['file']['tmp_name'];
$dpto = $_POST['dpto'];
$clave_asig = $_POST['clave_asig'];
$tipo_doc = $_POST['tipo_doc'];
$extensiones_id = $_POST['extensiones_id'];
$nivel = $_POST['nivel'];
$target_dir = $config['dir_subida'];
$target_ext = strtolower(pathinfo($uploaded_name, PATHINFO_EXTENSION));

$doc = new Documento($dpto, $clave_asig, $tipo_doc, $extensiones_id, $nivel );
$target_file = $target_dir . '/' . $doc->basename() . '.' . $target_ext;

// TODO Valida extensión según el hueco sea para odt/doc/docx, odt/doc/docx/pdf o pdf
if ($target_ext != 'odt' && $target_ext != 'doc' && $target_ext != 'docx' && $target_ext != 'pdf') {
    echo json_encode([ 'error' => 'Formatos aceptados: odt, doc, docx, pdf' ]);
    return;
}

// Valida fichero existe
if (file_exists($target_file)) {
    echo json_encode([ 'error' => 'El fichero ya existe, bórralo primero' ]);
}

move_uploaded_file($uploaded_tmp_name, $target_file);
$doc = new Documento($dpto, $clave_asig, $tipo_doc, $extensiones_id, $nivel );

$celda_html = $templates->render('parcial/celda', [ 'doc' => $doc ]);
echo json_encode([ 'ok' => $celda_html ]);

?>
