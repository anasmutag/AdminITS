<?php

class Pagovalidacion extends ActiveRecord {
    public function cargarPagoValidacion($validacion) {
        return $this->find_first("conditions: id_validacion = $validacion");
    }
    
    public function cargarNumeroReciboPago() {
        return $this->find("columns: max(numero_recibo_pagovalidacion) as recibo");
    }
}
