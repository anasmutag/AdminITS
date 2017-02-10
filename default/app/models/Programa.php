<?php

class Programa extends ActiveRecord {
    public function programas() {
        return $this->find('columns: id_programa, codigo_programa, nombre_programa, valor_semestre_programa', 'order: nombre_programa');
    }
}
