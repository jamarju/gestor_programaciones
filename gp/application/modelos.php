<?php

require_once '../application/config.inc.php';
require_once '../vendor/autoload.php';


class Extensiones {
    const ODT_DOC_DOCX      = 0;
    const PDF               = 1;
    const ODT_DOC_DOCX_PDF  = 2;

    const LISTA = [
        [ 'odt', 'doc', 'docx' ],
        [ 'pdf' ],
        [ 'odt', 'doc', 'docx', 'pdf' ] 
    ];

    public static function lista($ext_id) {
        return Extensiones::LISTA[$ext_id];
    }
}


class TipoDoc {
    const PROGRAMACION          = 0;
    const PROGRAMACION_GENERAL  = 1;
    const RESUMEN               = 2;
    const MEMORIA_1EV           = 3;
    const MEMORIA_2EV           = 4;
    const MEMORIA_FINAL         = 5;

    const SUFIJO = [ 'PR', 'PRG', 'RES', 'M1', 'M2', 'MF' ];

    public static function sufijo($tipo_doc_id) {
        return TipoDoc::SUFIJO[$tipo_doc_id];
    }
}


class Documento {
    public $dpto;
    public $clave_asig;
    public $tipo_doc;
    public $extensiones_id;
    public $nivel;

    private $nombre;    // nombre del fichero (si existe) o NULL

    private function busca() {
        global $config;
        $dir_subida = $config['dir_subida'];

        $this->nombre = NULL;

        //error_log("lista=" . join(",", Extensiones::lista($this->extensiones_id)));
        foreach (Extensiones::lista($this->extensiones_id) as $ext) {
            $nombre = $this->basename() . '.' . $ext;

            //error_log("Buscando $dir_subida/$nombre", 0);
            if (file_exists($dir_subida . '/' . $nombre)) {
                $this->nombre = $nombre;
            }
        }
    }

    public function __construct($dpto, $clave_asig, $tipo_doc, $extensiones_id, $nivel = NULL)
    {
        $this->dpto = $dpto;
        $this->clave_asig = $clave_asig;
        $this->tipo_doc = $tipo_doc;
        $this->extensiones_id = $extensiones_id;
        $this->nivel = $nivel;

        $this->busca();
    }

    /*
    public static function a_partir_de_id($id) {
        $partes = explode('_', $id);
        if (sizeof($partes) == 5) { // documento de nivel
            $i = new self();
            $i->dpto = $partes[0];
            $i->clave_asig = $partes[1];
            $i->
        }
        elseif (sizeof($partes) == 4) { // documento general de asignatura

        }
        $instance->loadByID( $id );
        return $i;
    }
    */


    public function basename() {
        if (is_null($this->nivel) || empty($this -> nivel)) {
            $bn = $this->dpto . '_' . $this->clave_asig . '_' . TipoDoc::sufijo($this->tipo_doc);
        } else {
            $bn = $this->dpto . '_' . $this->clave_asig . '_' . $this->nivel . '_' 
                . TipoDoc::sufijo($this->tipo_doc);
        }
        //error_log("Nivel: " . $this->nivel . " BASENAME: $bn");
        return $bn;
    }

    public function id() {
        return $this->basename() . '_' . $this->extensiones_id;
    }

    public function existe() {
        return !is_null($this->nombre);
    }

    public function href() {
        global $config;
        if (!is_null($this->nombre)) {
            return $config['dir_subida'] . '/' . $this->nombre;
        }
    }

    public function nombre() {
        return $this->nombre;
    }

    public function lista_extensiones() {
        return Extensiones::lista($this->extensiones_id);
    }
}


class Dpto {
    public $clave;
    public $nombre;
    public $asignaturas;

    public function __construct($clave, $nombre) {
        $this->clave = $clave;
        $this->nombre = $nombre;
    }
}


class Asignatura {
    public $dpto;
    public $clave;
    public $nombre;
    public $niveles;

    public $documentos;

    /** Busca los documentos generales de la asignatura y los mete en el
      * array documentos en el mismo orden en que se van a presentar.
      */
    private function busca_docs() {
        $this->documentos = [
            new Documento($this->dpto->clave, $this->clave,
                TipoDoc::PROGRAMACION_GENERAL, Extensiones::ODT_DOC_DOCX),
            new Documento($this->dpto->clave, $this->clave,
                TipoDoc::PROGRAMACION_GENERAL, Extensiones::PDF),
            new Documento($this->dpto->clave, $this->clave, 
                TipoDoc::MEMORIA_1EV, Extensiones::ODT_DOC_DOCX_PDF),
            new Documento($this->dpto->clave, $this->clave, 
                TipoDoc::MEMORIA_2EV, Extensiones::ODT_DOC_DOCX_PDF),
            new Documento($this->dpto->clave, $this->clave, 
                TipoDoc::MEMORIA_FINAL, Extensiones::ODT_DOC_DOCX_PDF)
        ];
    }

    public function __construct($clave, $nombre, $dpto) {
        $this->clave = $clave;
        $this->nombre = $nombre;
        $this->dpto = $dpto;
        $dpto->asignaturas[] = $this;

        $this->busca_docs();
    }
}


class Nivel {
    public $asignatura;
    public $clave;

    public $documentos;

    /** Busca los documentos de cada nivel
     */
    private function busca_docs() {
        $this->documentos = [
            new Documento($this->asignatura->dpto->clave, $this->asignatura->clave, 
                TipoDoc::PROGRAMACION, Extensiones::ODT_DOC_DOCX, $this->clave),
            new Documento($this->asignatura->dpto->clave, $this->asignatura->clave,
                TipoDoc::PROGRAMACION, Extensiones::PDF, $this->clave),
            new Documento($this->asignatura->dpto->clave, $this->asignatura->clave,
                TipoDoc::RESUMEN, Extensiones::ODT_DOC_DOCX, $this->clave),
            new Documento($this->asignatura->dpto->clave, $this->asignatura->clave,
                TipoDoc::RESUMEN, Extensiones::PDF, $this->clave)
        ];
    }

    public function __construct($clave, $asignatura) {
        $this->clave = $clave;
        $this->asignatura = $asignatura;
        $asignatura->niveles[] = $this;

        $this->busca_docs();
    }
}


class HojaConfig {
    private $objPHPExcel;

    public function __construct() {
        global $config;
        $dir_conf = $config['dir_conf'];
        $config_ods = $dir_conf . '/asignaturas.ods';
        $this->objPHPExcel = PHPExcel_IOFactory::load($config_ods);
    }

    public function lee_dptos() {
        // Lee datos
        $sheet = $this->objPHPExcel->getSheetByName('DEPARTAMENTOS');

        // Cutre-validación
        if ($sheet->getCellByColumnAndRow(0, 1) != 'Nombre dep') {
            throw new Exception("Formato del ODS mal");
        }

        $hr = $sheet->getHighestRow();
        $hc = $sheet->getHighestColumn();
        $dptos = [];

        for ($r = 2; $r <= $hr; $r++) {
            $nombre = $sheet->getCellByColumnAndRow(0, $r)->getValue();
            $clave = $sheet->getCellByColumnAndRow(1, $r)->getValue();
            $dpto = New Dpto($clave, $nombre);
            $dptos[] = $dpto;
        }

        return $dptos;
    }

    public function lee_dpto($clave_dpto) {

        // Nombre departamento
        $sheet = $this->objPHPExcel->getSheetByName('DEPARTAMENTOS');

        // Valida el formato del documento (cutremente)
        if ($sheet->getCellByColumnAndRow(0, 1) != 'Nombre dep') {
            throw new Exception("Formato del ODS mal");
        }

        $hr = $sheet->getHighestRow();

        for ($r = 2; $r <= $hr; $r++) {
            $nombre = $sheet->getCellByColumnAndRow(0, $r)->getValue();
            $clave = $sheet->getCellByColumnAndRow(1, $r)->getValue();
            //error_log("clave: $clave", 0);
            if ($clave == $clave_dpto) {
                $dpto = new Dpto($clave, $nombre);
            }
        }
        if (is_null($dpto)) {
            throw new Exception("No existe el departamento " . $clave_dpto);
        }

        // Asignaturas
        $sheet = $this->objPHPExcel->getSheetByName('ASIGNATURAS');
        $hr = $sheet->getHighestRow();
        $hc = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());

        // Lee nombres de niveles (C1:Cx)
        $claves_nivel = [];
        for ($c = 3; $c < $hc; $c++) {
            $claves_nivel[] = $sheet->getCellByColumnAndRow($c, 1)->getValue();
        }

        // Itera asignaturas del departamento
        for ($r = 2; $r <= $hr; $r++) {
            $d = $sheet->getCellByColumnAndRow(0, $r);
            if ($d != $clave_dpto) continue;

            // Lee nombre y clave de la asignatura
            $nombre_asig = $sheet->getCellByColumnAndRow(1, $r)->getValue();
            $clave_asig  = $sheet->getCellByColumnAndRow(2, $r)->getValue();

            $asignatura = new Asignatura($clave_asig, $nombre_asig, $dpto);
            
            // Lista niveles marcados con 'x'
            $niveles = [];
            for ($c = 3; $c < $hc; $c++) {
                if ($sheet->getCellByColumnAndRow($c, $r)->getValue() != '') {
                    $nivel = new Nivel($claves_nivel[$c - 3], $asignatura);
                }
            }
        }
        return $dpto;
    }
}

?>
