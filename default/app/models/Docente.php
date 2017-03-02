<?php

class Docente extends ActiveRecord {
    public function validarDocente($identificacion,$contrasena){
        $contrasena = addslashes($contrasena);
        $identificacion = addslashes($identificacion);
        
        return $this->find_by_sql('SELECT `validar_docente`("'.$identificacion.'","'.$contrasena.'") AS RESUL;');
    }
    
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
