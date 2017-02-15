<?php

class Acta extends ActiveRecord {
    public function validarActaAlumno($documento) {
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
                    where identificacion_alumno = $documento
                )");
    }
    
    public function cargarAniosEgresadosPrograma($programa) {
        return $this->find("columns: year(fecha_acta) as anio",
                "join: join alumno on acta.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: programa.id_programa = $programa
                    group by anio",
                "order: anio");
    }
    
    public function cargarActasPromocion($sede, $acta) {
        return $this->find("columns: fecha_acta,item_acta,lugar_expedicion_acta,identificacion_alumno,nombre_alumno,apellido_alumno,nombre_programa",
                "join: join alumno on acta.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: lugar_expedicion_acta = '$sede'
                    and numero_acta = $acta",
                "order: item_acta");
    }
}
