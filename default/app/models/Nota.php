<?php

class Nota extends ActiveRecord {
    public function cargarMateriasAlumno($id) {
        return $this->find("columns: id_materia", "conditions: id_alumno = 6 group by id_materia", "order: id_materia");
    }
    
    public function cargarNotasMateriasAlumno($id) {
        return $this->find("conditions: id_alumno = $id", "order: id_materia");
    }
    
    public function cargarTiposNotas($sede, $materia) {
        return $this->find_by_sql("select count(*) as resultado"
                . " from ("
                    . "select id_tiponota"
                . " from nota join alumno on nota.id_alumno = alumno.id_alumno"
                    . " join matricula on alumno.id_alumno = matricula.id_alumno"
                    . " join materia on nota.id_materia = materia.id_materia"
                    . " join materiaprograma on materia.id_materia = materiaprograma.id_materia"
                . " where id_estadomatricula = 1"
                    . " and matricula.id_semestre = semestre"
                    . " and nota.id_materia = $materia"
                    . " and id_sede = $sede"
                    . " and id_tiponota <> 4"
                . " group by id_tiponota) as table1");
    }
    
    public function cargarNotasMateria($id, $materia) {
        return $this->find("columns: nota.id_tiponota,valor_nota",
                "join: join materia on nota.id_materia = materia.id_materia
                    join materiaprograma on materia.id_materia = materiaprograma.id_materia
                    join tiponota on nota.id_tiponota = tiponota.id_tiponota",
                "conditions: id_alumno = $id
                    and materia.id_materia = $materia",
                "order: nota.id_tiponota");
    }
    
    public function cargarNotasCv($alumno, $materia) {
        if ($this->exists("id_alumno = $alumno and id_materia = $materia and id_tiponota = 4")) {
            return true;
        } else {
            return false;
        }
    }
}
