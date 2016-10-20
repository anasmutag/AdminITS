<?php

class Formapago extends ActiveRecord {
    public function formapagos() {
        return $this->find();
    }
}
