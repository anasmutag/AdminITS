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
    
    public function cargarAniosEgresadosPrograma($programa) {
        return $this->find("columns: year(fecha_acta) as anio",
                "join: join alumno on acta.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: programa.id_programa = $programa
                    group by anio",
                "order: anio");
    }
    
    public function cargarEgresados($page = 1, $programa, $anio) {
        /*return $this->paginate_by_sql("select id_alumno,valor_nota"
                . " from nota",
                "page: $page", "per_page: 10");*/
        
        return $this->paginate_by_sql("select alumnoprograma.id_programa,alumno.id_alumno,alumno.identificacion_alumno,alumno.nombre_alumno,alumno.apellido_alumno"
                . " from acta join alumno on acta.id_alumno = alumno.id_alumno"
                    . " join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno"
                . " where id_programa = $programa"
                    . " and year(fecha_acta) = $anio"
                . " order by year(fecha_acta) and alumno.apellido_alumno",
                "page: $page", "per_page: 10");
    }
}
