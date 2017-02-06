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
    
    public function cargarDatosEgresado($documento) {
        return $this->find("columns: programa.id_programa,programa.nombre_programa,egresado.*",
                "join: join egresadoprograma on egresado.id_egresado = egresadoprograma.id_egresado
                    join programa on egresadoprograma.id_programa = programa.id_programa",
                "conditions: identificacion_egresado = $documento");
    }
    
    public function cargarDatosEgresadoActualizar($egresado) {
        return $this->find_first("conditions: id_egresado = $egresado");
    }
}
