<?php
require_once '../vendor/autoload.php';
require_once '../application/modelos.php';

$templates = new League\Plates\Engine('../vistas');

// Lee querystring
$clave_dpto = $_GET['d'];

// Lee datos del departamento
$hoja_config = new HojaConfig();
try {
    $dpto = $hoja_config->lee_dpto($clave_dpto);

    //echo "<PRE>"; print_r($dpto); echo "</PRE>";
    echo $templates->render('asignaturas', [ 'dpto' => $dpto ]);
} catch (Exception $e) {
    echo $templates->render('asignaturas', [ 'error' => $e->getMessage() ]);
}

// Renderiza vista
?>
