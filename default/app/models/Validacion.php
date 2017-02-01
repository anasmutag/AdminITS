<?php

class Validacion extends ActiveRecord {
    /*public function cargarNumeroValidacionesAlumno($id) {
        return $this->find("columns: count(*)",
                "join: join pagovalidacion on validacion.id_validacion = pagovalidacion.id_validacion",
                "conditions: id_alumno = (
                    select id_alumno
                    from alumno
                    where identificacion_alumno = $id
                ) and estado_pagovalidacion = 1");
    }*/
    
    public function cargarValidacionesAlumno($id) {
        return $this->find("columns: validacion.id_validacion,codigo_materia,nombre_materia,semestre.id_semestre",
                "join: join pagovalidacion on validacion.id_validacion = pagovalidacion.id_validacion
                    join materia on validacion.id_materia = materia.id_materia
                    join alumno on validacion.id_alumno = alumno.id_alumno
                    join matricula on alumno.id_alumno = matricula.id_alumno
                    join semestre on matricula.id_semestre = semestre.id_semestre",
                "conditions: identificacion_alumno = $id and estado_pagovalidacion = 1",
                "order: semestre.id_semestre");
    }
    
    public function cargarNotasValidacionesMateriasAlumno($id) {
        return $this->find("conditions: id_alumno = $id");
    }
}
