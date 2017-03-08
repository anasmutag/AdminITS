<?php

class Programa extends ActiveRecord {
    public function programas() {
        return $this->find('columns: id_programa, codigo_programa, nombre_programa, valor_semestre_programa', 'order: nombre_programa');
    }
    
    public function programasDocente($docente) {
        return $this->find("columns: programa.*",
                "join: join materiaprograma on programa.id_programa = materiaprograma.id_programa
                    join materia on materiaprograma.id_materia = materia.id_materia
                    join materiadocente on materia.id_materia = materiadocente.id_materia",
                "conditions: id_docente = $docente
                    group by codigo_programa",
                "order: nombre_programa");
    }
}
