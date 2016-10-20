<?php

class Pais extends ActiveRecord {
    public function paises() {
        return $this->find("columns: id_pais, nombre_pais"," id_idioma=" . LANGUAGE_PATH . " order by nombre_pais asc");
    }
}
