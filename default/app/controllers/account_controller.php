<?php

Load::models('Usuario');

class AccountController extends AppController {
    function logearAdmin() {
        View::select(NULL, NULL);
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $identificacion = Input::request('identificacion');
        $contrasena = Input::request('contrasena');
        
        $usuario = new Usuario();
        
        if($usuario->validarUsuario($identificacion, $contrasena)->RESUL == true){
            $auth = new Auth("model", "class: usuario", "identificacion_usuario: $identificacion");

            if($auth->authenticate()){
                $arr['res'] = 'ok';
            }else{
                $arr['msg'] = 'Ocurrio un error inesperado. Intenta nuevamente';
            }
        }else{
            $arr['msg'] = 'Identificacion o contraseña invalida';
        }

        exit(json_encode($arr));
    }
    public function salirAdmin() {
        Auth::destroy_identity();
        
        Router::redirect("/");
    }
}
