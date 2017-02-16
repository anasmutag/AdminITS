<?php

class Materiaprograma extends ActiveRecord {
    public function cargarDatosNotasConvalidacion($programa, $semestre) {
        return $this->find("columns: materiaprograma.id_materia,codigo_materia,nombre_materia,semestre",
                "join: join materia on materiaprograma.id_materia = materia.id_materia",
                "conditions: id_programa = $programa
                    and semestre = $semestre",
                "order: semestre,nombre_materia");
    }
}
