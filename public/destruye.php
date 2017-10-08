<?php
require_once '../vendor/autoload.php';
include_once('../application/config.inc.php');

// Este controlador se debe llamar con AJAX y devuelve un JSON con la respuesta ('ok' o 'error')
header('Content-type: application/json');

//$target_name = pathinfo($_POST['nombre'], PATHINFO_FILENAME);   // sanea el nombre
$target_name = $_POST['nombre'];
$target_file = $config['dir_subida'] . '/' . $target_name;

// Valida fichero existe
if (! file_exists($target_file)) {
    echo json_encode([ 'error' => 'El fichero ' . $target_file . ' no existe' ]);
    return;
}

unlink($target_file);

echo json_encode([ 'ok' => 1 ]);

?>
