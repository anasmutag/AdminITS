<?php

class Periododocente extends ActiveRecord {
    public function cargarPeriodosActivos($documento) {
        return $this->find("columns: id_periododocente,fecha_inicio_periododocente,fecha_fin_periododocente",
                "join: join docente on periododocente.id_docente = docente.id_docente",
                "conditions: identificacion_docente = $documento");
    }
    
    public function cargarPeriodoActivoDocente($periododocente) {
        return $this->find_first("conditions: id_periododocente = $periododocente");
    }
}
