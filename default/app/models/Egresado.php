<?php

class Egresado extends ActiveRecord {
    public function validarDocumentoAlumno($documento) {
        if ($this->exists("identificacion_egresado = '" . $documento . "'")) {
            return true;
        } else {
            return false;
        }
    }
    
    public function cargarProgramasEgresados() {
        return $this->find_by_sql("select programa.*
                from egresado join egresadoprograma on egresado.id_egresado = egresadoprograma.id_egresado
                    join programa on egresadoprograma.id_programa = programa.id_programa
                group by programa.id_programa");
    }
}
