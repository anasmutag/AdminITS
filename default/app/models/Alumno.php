<?php

class Alumno extends ActiveRecord {
    public function cargarDatosAlumno($id) {
        return $this->find_first("conditions: id_alumno = $id");
    }
    
    public function cargarDatosAlumnoActualizar($id) {
        return $this->find_first("conditions: identificacion_alumno = $id");
    }
    
    public function cargarDatosAlumnoActa($id) {
        return $this->find("columns: alumno.id_alumno,alumno.identificacion_alumno,alumno.nombre_alumno,alumno.apellido_alumno,programa.id_programa,programa.nombre_programa",
                "join: join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: identificacion_alumno = $id");
    }
    
    public function cargarIdAlumno($documento) {
        return $this->find("columns: id_alumno",
                "conditions: identificacion_alumno = $documento");
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
