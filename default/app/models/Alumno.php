<?php

class Alumno extends ActiveRecord {
    public function cargarDatosAlumno($id) {
        return $this->find_first("conditions: id_alumno = $id");
    }
    
    public function cargarDatosAlumnoActualizar($id) {
        return $this->find_first("conditions: identificacion_alumno = $id");
    }
    
    public function validarAlumno($id, $correo) {
        if ($this->exists("identificacion_alumno = '" . $id . "'") || $this->exists("correo_electronico_alumno = '" . $correo . "'")) {
            return true;
        } else {
            return false;
        }
    }
    
    public function validarDocumentoAlumno($documento) {
        if ($this->exists("identificacion_alumno = '" . $documento . "'")) {
            return true;
        } else {
            return false;
        }
    }
}
