<?php

class Semestre extends ActiveRecord {
    public function semestres() {
        return $this->find();
    }
}
