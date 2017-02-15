<?php

class Pagov extends ActiveRecord {
    public function cargarNumeroReciboPago() {
        return $this->find("columns: max(numero_recibo_pagov) as recibo");
    }
}
