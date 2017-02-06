<?php

class Seguimientoegresado extends ActiveRecord {
    public function cargarUltimoSeguimientoEgresado($documento) {
        return $this->find("conditions: id_egresado = (
                    select id_egresado
                    from egresado
                    where identificacion_egresado = $documento
                )",
                "order: fecha_seguimientoegresado desc limit 1");
    }
}
