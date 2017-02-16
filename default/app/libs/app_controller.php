<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
class AppController extends Controller {
    final protected function initialize() {
        if(Auth::is_valid()){
            //instanciamos a la clase MyAcl, y le indicamos el ini a utlizar
            $acl = new Acl('privilegios');  //si no se especifica el archivo a usar, por defecto busca en privilegios.ini

            $modulo = $this->module_name; //obtenermos el modulo actual
            $controlador = $this->controller_name; //obtenemos el controlador actual
            $accion = $this->action_name; //y obtenemos la accion actual

            $privilegio = Auth::get('rol_usuario');

            if (!$acl->check($privilegio, $modulo, $controlador, $accion)) { //verificamos si tiene ó no privilegios
                //no dejamos que entre al contenido de la url si no tiene permisos
                View::select(NULL,'restriccion');
                
                return false;
            }
        }
    }

    final protected function finalize() {
        
    }
}
