<?php

class Usuario extends ActiveRecord {
    public function validarUsuario($identificacion,$contrasena){
        $contrasena = addslashes($contrasena);
        $identificacion = addslashes($identificacion);
        
        return $this->find_by_sql('SELECT `validar_usuario`("'.$identificacion.'","'.$contrasena.'") AS RESUL;');
    }
}
