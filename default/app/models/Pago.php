<?php

class Pago extends ActiveRecord {
    public function cargarNumeroReciboPago() {
        return $this->find("columns: max(numero_recibo_pago) as recibo");
    }
}
