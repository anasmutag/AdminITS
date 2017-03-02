<?php

class Materiaprograma extends ActiveRecord {
    public function cargarDatosNotasConvalidacion($programa, $semestre) {
        return $this->find("columns: materiaprograma.id_materia,codigo_materia,nombre_materia,semestre",
                "join: join materia on materiaprograma.id_materia = materia.id_materia",
                "conditions: id_programa = $programa
                    and semestre = $semestre",
                "order: semestre,nombre_materia");
    }
    
    public function materiasprograma($programa) {
        return $this->find("columns: materia.id_materia, materia.codigo_materia, materia.nombre_materia",
                "join: join materia on materiaprograma.id_materia = materia.id_materia",
                "conditions: id_programa = " . $programa,
                "order: nombre_materia" );
    }
    
    public function materiasProgramaDocente($programa, $docente) {
        return $this->find("columns: materia.id_materia, materia.codigo_materia, materia.nombre_materia",
                "join: join materia on materiaprograma.id_materia = materia.id_materia
                    join materiadocente on materia.id_materia = materiadocente.id_materia",
                "conditions: id_programa = $programa
                    and id_docente = $docente",
                "order: nombre_materia" );
    }
}
