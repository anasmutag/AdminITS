<?php

class Region extends ActiveRecord {
    public function regiones($pais) {
        $pais = filter_var($pais, FILTER_SANITIZE_STRING);
        
        return $this->find("columns: id_region, nombre_region", "id_pais='" . $pais . "' and id_idioma=" . LANGUAGE_PATH . " order by nombre_region asc");
    }
}
