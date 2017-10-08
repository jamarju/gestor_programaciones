<?php
require_once '../vendor/autoload.php';
require_once '../application/modelos.php';

$templates = new League\Plates\Engine('../vistas');

$path = '../data/asignaturas.ods';
$objPHPExcel = PHPExcel_IOFactory::load($path);

// Lee datos del departamento
$hoja_config = new HojaConfig();
try {
    $dptos = $hoja_config->lee_dptos();
    echo $templates->render('departamentos', [ 'dptos' => $dptos ]);
} catch (Exception $e) {
    echo $templates->render('departamentos', [ 'error' => $e->getMessage() ]);
}

?>
