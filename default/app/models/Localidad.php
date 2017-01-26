<?php

class Localidad extends ActiveRecord {
    public function localidades($pais, $region) {
        $pais = filter_var($pais, FILTER_SANITIZE_STRING);
        $region = filter_var($region, FILTER_SANITIZE_STRING);
        
        return $this->find("columns: id_localidad, nombre_localidad", "id_pais='" . $pais . "' and id_region='" . $region . "' and id_idioma=" . LANGUAGE_PATH . " order by nombre_localidad asc" );
    }
    
    public function cargarDatosLocalidad($localidad, $idioma) {
        return $this->find("columns: id_region,id_pais", "conditions: id_localidad = $localidad and id_idioma = $idioma");
    }
}
