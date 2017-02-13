<?php

class Docente extends ActiveRecord {
    public function validarDocumento($documento) {
        if ($this->exists("identificacion_docente = '" . $documento . "'")) {
            return true;
        } else {
            return false;
        }
    }
    
    public function cargarDatosDocente($documento) {
        return $this->find_first("columns: identificacion_docente,nombre_docente,apellido_docente",
                "conditions: identificacion_docente = $documento");
    }
}
