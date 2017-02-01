<?php

class Pagovalidacion extends ActiveRecord {
    public function cargarPagoValidacion($validacion) {
        return $this->find_first("conditions: id_validacion = $validacion");
    }
}
