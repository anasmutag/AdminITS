<?php

class Tipodocumento extends ActiveRecord {
    public function tipoDocumentos() {
        return $this->find();
    }
}
