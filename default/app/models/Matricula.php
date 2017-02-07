<?php

class Matricula extends ActiveRecord {
    public function cargarDatosMatriculaActualizar($id) {
        return $this->find_first("conditions: id_matricula = $id");
    }
    
    public function cargarDatosMatricula($id) {
        return $this->find("columns: matricula.id_matricula,numero_matricula,fecha_matricula,numero_cuotas_matricula,numero_cuotaspagadas_matricula,abreviatura_tipodocumento,identificacion_alumno,nombre_alumno,apellido_alumno,nombre_programa,valor_semestre_programa,nombre_semestre,formapago.id_formapago as formapago,nombre_formapago",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join tipodocumento on alumno.id_tipodocumento = tipodocumento.id_tipodocumento
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa
                    join semestre on matricula.id_semestre = semestre.id_semestre
                    join formapago on matricula.id_formapago = formapago.id_formapago",
                "conditions: alumno.identificacion_alumno = $id",
                "order: numero_matricula desc limit 1");
    }
    
    /*public function cargarDatosValidacion($id) {
        
    }*/
    
    public function cargarDatosMatriculaInactiva($id) {
        return $this->find("columns: matricula.*,alumno.*,programa.*",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: matricula.id_estadomatricula = 2 and alumno.identificacion_alumno = $id",
                "order: id_semestre desc limit 1");
    }
    
    public function validarIdentificacion($id) {
        if ($this->find("columns: count(*) as resultado",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno",
                "conditions: identificacion_alumno = $id")[0]->resultado != 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function ultimoNumero() {
        return $this->find("columns: numero_matricula as resultado", "order: numero_matricula desc limit 1");
    }
    
    public function alumnos() {
        return $this->find("columns: matricula.id_alumno,nombre_alumno,apellido_alumno,programa.id_programa",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: id_semestre = 3
                    and id_estadomatricula = 2
                    and id_estadoegresado = 2",
                "order: apellido_alumno");
    }
    
    public function programas() {
        return $this->find("columns: programa.id_programa,nombre_programa",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: id_semestre = 3
                    and id_estadomatricula = 2
                    and id_estadoegresado = 2
                    group by programa.id_programa",
                "order: programa.id_programa");
    }
}
