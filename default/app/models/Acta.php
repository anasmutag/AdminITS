<?php

class Acta extends ActiveRecord {
    public function validarDocumentoAlumno($documento) {
        if ($this->exists("id_alumno = (select id_alumno from alumno where identificacion_alumno = $documento)")) {
            return true;
        } else {
            return false;
        }
    }
    
    public function cargarDatosActaAlumno($documento) {
        return $this->find("conditions: id_alumno = (
                    select id_alumno
                    from alumno
                    where identificacion_alumno = 123456
                )");
    }
}
