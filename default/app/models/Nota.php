<?php

class Nota extends ActiveRecord {
    public function cargarMateriasAlumno($id) {
        return $this->find("columns: id_materia", "conditions: id_alumno = 6 group by id_materia", "order: id_materia");
    }
    
    public function cargarNotasMateriasAlumno($id) {
        return $this->find("conditions: id_alumno = $id", "order: id_materia");
    }
}
