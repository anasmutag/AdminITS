<?php

Load::models('Alumno', 'Matricula', 'Alumnoprograma', 'Tipodocumento', 'Programa', 'Semestre', 'Formapago', 'Pais', 'Region', 'Localidad', 'Pago');

class ManagementController extends AppController {
    function administracion() {
        //View::template('general_template');
        
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    function matricula() {
        if(Auth::is_valid()){
            View::template('general_template');
            
            /*$tipodocumento = new Tipodocumento();
            $programa = new Programa();
            $semestre = new Semestre();
            $formapago = new Formapago();
            $pais = new Pais();
            $region = new Region();
            
            $this->tipodocumentos = $tipodocumento->tipoDocumentos();
            $this->programas = $programa->programas();
            $this->semestres = $semestre->semestres();
            $this->formapagos = $formapago->formapagos();
            $this->paises = $pais->paises();
            $this->regiones = $region->regiones(82);*/
        }else{
            Router::redirect("/");
        }
    }
    
    function formulariomatricula() {
        View::template(NULL);
        
        $tipodocumento = new Tipodocumento();
        $programa = new Programa();
        $semestre = new Semestre();
        $formapago = new Formapago();
        $pais = new Pais();
        $region = new Region();
        $localidad = new Localidad();
        $matricula = new Matricula();
        
        $bandera = Input::request('tipoForm');
        $documento = filter_var(Input::request('documento'), FILTER_SANITIZE_STRING);
        $this->bandera = $bandera;
        $this->tipodocumentos = $tipodocumento->tipoDocumentos();
        $this->programas = $programa->programas();
        $this->semestres = $semestre->semestres();
        $this->formapagos = $formapago->formapagos();
        $this->paises = $pais->paises();
        $this->regiones = $region->regiones(82);
        $this->numeromatricula = $matricula->ultimoNumero()[0]->resultado + 1;
        
        if($bandera != 0){
            $datos = $matricula->cargarDatosMatriculaInactiva($documento);
            $this->datos = $datos;
            $datoslocalidad = $localidad->cargarDatosLocalidad($datos[0]->lugar_nacimiento_alumno, LANGUAGE_PATH);
            $this->datoslocalidad = $datoslocalidad;
            
            $this->localidades = $localidad->localidades($datoslocalidad[0]->id_pais, $datoslocalidad[0]->id_region);
            
            $datoslocalidadE = $localidad->cargarDatosLocalidad($datos[0]->lugar_expedicion_identificacion_alumno, LANGUAGE_PATH);
            $this->datoslocalidadE = $datoslocalidadE;
            
            $this->localidadesE = $localidad->localidades($datoslocalidadE[0]->id_pais, $datoslocalidadE[0]->id_region);
            
            $date = new DateTime($datos[0]->fecha_nacimiento_alumno);
            $this->fechanacimiento = $date->format('Y-m-d');
        }
    }
    
    public function obtenerRegiones($idpais = '0') {
        View::select(NULL, NULL);
        
        $region = new Region();
        
        echo json_encode($region->regiones($idpais));
    }
    
    public function obtenerLocalidades($idpais = '0', $idregion = '0') {
        View::select(NULL, NULL);
        
        $localidad = new Localidad();
        
        echo json_encode($localidad->localidades($idpais, $idregion));
    }
    
    public function consultarDocumentoEstudiante() {
        View::select(NULL, NULL);
        
        $alumno = new Alumno();
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $documento = Input::request('documento');
        
        if($alumno->validarDocumentoAlumno($documento)){
            $arr['res'] = 'ok';
        }else{
            $arr['msg'] = 'El número de documento no se encuentra registrado en nuestro sistema';
        }
        
        exit(json_encode($arr));
    }
    
    public function registrarEstudiante() {
        if(Auth::is_valid()){
            View::select(NULL, NULL);
            
            $alumno = new Alumno();
            $matricula = new Matricula();
            $alumnoprograma= new Alumnoprograma();
            
            /** Data **/
            $sede = filter_var(Input::request('sedematricula'), FILTER_SANITIZE_STRING);
            $tipoDocumento = filter_var(Input::request('tipodocumento'), FILTER_SANITIZE_STRING);
            $documento = filter_var(Input::request('numerodocumento'), FILTER_SANITIZE_STRING);
            $pais = filter_var(Input::request('paisE'), FILTER_SANITIZE_STRING);
            $region = filter_var(Input::request('regionE'), FILTER_SANITIZE_STRING);
            $localidad = filter_var(Input::request('localidadE'), FILTER_SANITIZE_STRING);
            $nombre = filter_var(Input::request('nombre'), FILTER_SANITIZE_STRING);
            $apellido = filter_var(Input::request('apellido'), FILTER_SANITIZE_STRING);
            $fechaNacimiento = filter_var(Input::request('fechanacimiento'), FILTER_SANITIZE_STRING);
            $paisNacimiento = filter_var(Input::request('paisN'), FILTER_SANITIZE_STRING);
            $regionNacimiento = filter_var(Input::request('regionN'), FILTER_SANITIZE_STRING);
            $localidadNacimiento = filter_var(Input::request('localidadN'), FILTER_SANITIZE_STRING);
            $direccion = filter_var(Input::request('direccion'), FILTER_SANITIZE_STRING);
            $telefono = filter_var(Input::request('telefono'), FILTER_SANITIZE_STRING);
            $correoElectronico = filter_var(Input::request('correoelectronico'), FILTER_SANITIZE_STRING);
            $nombreAcudiente = filter_var(Input::request('nombreA'), FILTER_SANITIZE_STRING);
            $apellidoAcudiente = filter_var(Input::request('apellidoA'), FILTER_SANITIZE_STRING);
            $telefonoAcudiente = filter_var(Input::request('telefonoA'), FILTER_SANITIZE_STRING);
            $empresa = filter_var(Input::request('empresa'), FILTER_SANITIZE_STRING);
            $cargo = filter_var(Input::request('cargo'), FILTER_SANITIZE_STRING);
            $direccionEmpresa = filter_var(Input::request('direccionE'), FILTER_SANITIZE_STRING);
            $telefonoEmpresa = filter_var(Input::request('telefonoE'), FILTER_SANITIZE_STRING);
            $programa = filter_var(Input::request('programa'), FILTER_SANITIZE_STRING);
            $periodo = filter_var(Input::request('periodomatricula'), FILTER_SANITIZE_STRING);
            $codigoEstudiante = filter_var(Input::request('codigoestudiantil'), FILTER_SANITIZE_STRING);
            $numeroMatricula = filter_var(Input::request('numeromatricula'), FILTER_SANITIZE_STRING);
            $fechaMatricula = filter_var(Input::request('fechamatricula'), FILTER_SANITIZE_STRING);
            $formaPago = filter_var(Input::request('formapago'), FILTER_SANITIZE_STRING);
            $numeroCuotas = filter_var(Input::request('numerocuotas'), FILTER_SANITIZE_STRING);
            /**********/
            
            $arr['res'] = 'fail';
            $arr['msg'] = '';
            
            /*$validacion = $this->validacion($sede);
            $arr['msg'] = $validacion;*/
            
            if($alumno->validarAlumno($documento, $correoElectronico)) {
                $arr['msg'] = 'El número de identificación o correo electrónico ya se encuentra registrado';
            } else {
                $alumno->begin();
                
                $alumno->identificacion_alumno = $documento;
                $alumno->lugar_expedicion_identificacion_alumno = $localidad;
                $alumno->nombre_alumno = $nombre;
                $alumno->apellido_alumno = $apellido;
                $alumno->fecha_nacimiento_alumno = $fechaNacimiento;
                $alumno->lugar_nacimiento_alumno = $localidadNacimiento;
                $alumno->direccion_alumno = $direccion;
                $alumno->telefono_alumno = $telefono;
                $alumno->correo_electronico_alumno = $correoElectronico;
                $alumno->contrasena_alumno = $documento;
                $alumno->nombre_acudiente_alumno = $nombreAcudiente;
                $alumno->apellido_acudiente_alumno = $apellidoAcudiente;
                $alumno->telefono_acudiente_alumno = $telefonoAcudiente;
                $alumno->ocupacion_alumno = $cargo;
                $alumno->empresa_alumno = $empresa;
                $alumno->direccion_empresa_alumno = $direccionEmpresa;
                $alumno->telefono_empresa_alumno = $telefonoEmpresa;
                $alumno->id_tipodocumento = $tipoDocumento;
                
                if($alumno->save()) {
                    $matricula->numero_matricula = $numeroMatricula;
                    $matricula->fecha_matricula = $fechaMatricula;
                    
                    if(is_null($numeroCuotas)){
                        $matricula->numero_cuotas_matricula = 1;
                        $matricula->numero_cuotaspagadas_matricula = '0';
                    }else{
                        $matricula->numero_cuotas_matricula = $numeroCuotas;
                        $matricula->numero_cuotaspagadas_matricula = '0';
                    }
                    
                    $matricula->id_sede = $sede;
                    $matricula->id_alumno = $alumno->id_alumno;
                    $matricula->id_semestre = 1;
                    $matricula->id_periodo = $periodo;
                    $matricula->id_formapago = $formaPago;
                    
                    if($matricula->save()) {
                        $alumnoprograma->id_alumno = $alumno->id_alumno;
                        $alumnoprograma->id_programa = $programa;
                        
                        if($alumnoprograma->save()) {
                            $arr['res'] = 'ok';
                            $arr['msg'] = 'Registro de matricula del estudiante realizado con exito';
                            $arr['id'] = $documento;
                            
                            $alumno->commit();
                        } else {
                            $arr['msg'] = 'Ocurrio un error en el registro de matricula del estudiante. Intentar nuevamente';
                            
                            $alumno->rollback();
                        }
                    } else {
                        $arr['msg'] = 'Ocurrio un error en el registro de matricula del estudiante. Intentar nuevamente';
                        
                        $alumno->rollback();
                    }
                } else {
                    $arr['msg'] = 'El registro del estudiante no se llevo a cabo con exito. Intentar nuevamente';
                    
                    $alumno->rollback();
                }
            }
            
            exit(json_encode($arr));
        }else{
            Router::redirect("/");
        }
    }
    
    public function registrarMatricula() {
        if(Auth::is_valid()){
            View::select(NULL, NULL);
            
            $alumno = new Alumno();
            $matricula = new Matricula();
            
            /** Data **/
            $sede = filter_var(Input::request('sedematricula'), FILTER_SANITIZE_STRING);
            $tipoDocumento = filter_var(Input::request('tipodocumento'), FILTER_SANITIZE_STRING);
            $documento = filter_var(Input::request('numerodocumento'), FILTER_SANITIZE_STRING);
            $direccion = filter_var(Input::request('direccion'), FILTER_SANITIZE_STRING);
            $telefono = filter_var(Input::request('telefono'), FILTER_SANITIZE_STRING);
            $correoElectronico = filter_var(Input::request('correoelectronico'), FILTER_SANITIZE_STRING);
            $nombreAcudiente = filter_var(Input::request('nombreA'), FILTER_SANITIZE_STRING);
            $apellidoAcudiente = filter_var(Input::request('apellidoA'), FILTER_SANITIZE_STRING);
            $telefonoAcudiente = filter_var(Input::request('telefonoA'), FILTER_SANITIZE_STRING);
            $empresa = filter_var(Input::request('empresa'), FILTER_SANITIZE_STRING);
            $cargo = filter_var(Input::request('cargo'), FILTER_SANITIZE_STRING);
            $direccionEmpresa = filter_var(Input::request('direccionE'), FILTER_SANITIZE_STRING);
            $telefonoEmpresa = filter_var(Input::request('telefonoE'), FILTER_SANITIZE_STRING);
            $periodo = filter_var(Input::request('periodomatricula'), FILTER_SANITIZE_STRING);
            $codigoEstudiante = filter_var(Input::request('codigoestudiantil'), FILTER_SANITIZE_STRING);
            $numeroMatricula = filter_var(Input::request('numeromatricula'), FILTER_SANITIZE_STRING);
            $fechaMatricula = filter_var(Input::request('fechamatricula'), FILTER_SANITIZE_STRING);
            $formaPago = filter_var(Input::request('formapago'), FILTER_SANITIZE_STRING);
            $numeroCuotas = filter_var(Input::request('numerocuotas'), FILTER_SANITIZE_STRING);
            $semestre = filter_var(Input::request('semestre'), FILTER_SANITIZE_STRING);
            /**********/
            
            $arr['res'] = 'fail';
            $arr['msg'] = '';
            
            $alumno->begin();
            
            $alumno->cargarDatosAlumnoActualizar($documento);
            $alumno->direccion_alumno = $direccion;
            $alumno->telefono_alumno = $telefono;
            $alumno->correo_electronico_alumno = $correoElectronico;
            $alumno->nombre_acudiente_alumno = $nombreAcudiente;
            $alumno->apellido_acudiente_alumno = $apellidoAcudiente;
            $alumno->telefono_acudiente_alumno = $telefonoAcudiente;
            $alumno->ocupacion_alumno = $cargo;
            $alumno->empresa_alumno = $empresa;
            $alumno->direccion_empresa_alumno = $direccionEmpresa;
            $alumno->telefono_empresa_alumno = $telefonoEmpresa;
            $alumno->id_tipodocumento = $tipoDocumento;
            
            if($alumno->update()){
                $matricula->numero_matricula = $numeroMatricula;
                $matricula->fecha_matricula = $fechaMatricula;
                
                if(is_null($numeroCuotas)){
                    $matricula->numero_cuotas_matricula = 1;
                    $matricula->numero_cuotaspagadas_matricula = '0';
                }else{
                    $matricula->numero_cuotas_matricula = $numeroCuotas;
                    $matricula->numero_cuotaspagadas_matricula = '0';
                }
                
                $matricula->id_sede = $sede;
                $matricula->id_alumno = $alumno->id_alumno;
                $matricula->id_semestre = $semestre;
                $matricula->id_periodo = $periodo;
                $matricula->id_formapago = $formaPago;
                
                if($matricula->save()) {
                    $arr['res'] = 'ok';
                    $arr['msg'] = 'Registro de matricula del estudiante realizado con exito';
                    $arr['id'] = $documento;
                    
                    $alumno->commit();
                } else {
                    $arr['msg'] = 'Ocurrio un error en el registro de matricula del estudiante. Intentar nuevamente';
                    
                    $alumno->rollback();
                }
            }else{
                $arr['msg'] = 'El registro de matricula no se llevo a cabo con exito. Intentar nuevamente';
                
                $alumno->rollback();
            }
            
            exit(json_encode($arr));
        }else{
            Router::redirect("/");
        }
    }
    
    /*private function validacion($sede) {
        if($sede !== '1'){
            return true;
        }else{
            return false;
        }
    }*/
    
    public function pagomatricula($idAlumno = 0) {
        if(Auth::is_valid()){
            View::template('general_template');
            
            $matricula = new Matricula();
            
            $this->idAlumno = $idAlumno;
            
            if($idAlumno != 0) {
                $datos = $matricula->cargarDatosMatricula($idAlumno);
                
                $numerocuotas = $datos[0]->numero_cuotas_matricula;
                $cuotaspagadas = $datos[0]->numero_cuotaspagadas_matricula;
                
                $this->idMatricula = $datos[0]->id_matricula;
                $this->numeroMatricula = $datos[0]->numero_matricula;
                $this->fechaMatricula = $datos[0]->fecha_matricula;
                $this->numeroCuotas = $numerocuotas;
                $this->cuotasPagadas = $cuotaspagadas;
                $this->abreviaturaid = $datos[0]->abreviatura_tipodocumento;
                $this->id = $datos[0]->identificacion_alumno;
                $this->nombre = $datos[0]->nombre_alumno . ' ' . $datos[0]->apellido_alumno;
                $this->programa = $datos[0]->nombre_programa;
                $this->tipoPago = $datos[0]->formapago;
                
                if($numerocuotas === $cuotaspagadas){
                    $this->saldo = 1;
                }else{
                    $this->saldo = 0;
                }
            }else{
                $this->idMatricula = 0;
            }
        }else{
            Router::redirect("/");
        }
    }
    
    public function consultardocumentopagomatricula() {
        View::template(NULL);
        
        $matricula = new Matricula();
        $identificacion = filter_var(Input::request('documentopagomatricula'), FILTER_SANITIZE_STRING);
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        if($matricula->validarIdentificacion($identificacion)){
            $arr['res'] = 'ok';
        }else{
            $arr['msg'] = 'El número de documento no tiene registro de matricula';
        }
        
        exit(json_encode($arr));
    }
    
    public function registrarpagocontado() {
        View::template(NULL);
        
        $pago = new Pago();
        $matricula = new Matricula();
        $valor = filter_var(Input::request('valor'), FILTER_SANITIZE_STRING);
        $idmatricula = filter_var(Input::request('idMatricula'), FILTER_SANITIZE_STRING);
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $pago->begin();
        
        $pago->valor_pago = $valor;
        $pago->id_matricula = $idmatricula;
        $pago->id_mediopago = 1;
        
        if($pago->save()){
            $matricula->cargarDatosMatriculaActualizar($idmatricula);
            $matricula->numero_cuotaspagadas_matricula = 1;
            
            if($matricula->update()){
                $arr['res'] = 'ok';
                
                $pago->commit();
            }else{
                $arr['msg'] = 'Error al registrar el pago del valor de matricula';
                
                $pago->rollback();
            }
        }else{
            $arr['msg'] = 'Error al registrar el pago del valor de matricula';
            
            $pago->rollback();
        }
        
        exit(json_encode($arr));
    }
    
    public function registrarpagocuota() {
        View::template(NULL);
        
        $pago = new Pago();
        $matricula = new Matricula();
        $valor = filter_var(Input::request('valor'), FILTER_SANITIZE_STRING);
        $idmatricula = filter_var(Input::request('idMatricula'), FILTER_SANITIZE_STRING);
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $pago->begin();
        
        $pago->valor_pago = $valor;
        $pago->id_matricula = $idmatricula;
        $pago->id_mediopago = 1;
        
        if($pago->save()){
            $matricula->cargarDatosMatriculaActualizar($idmatricula);
            //$cuotaspagas = $matricula->numero_cuotaspagadas_matricula;
            $matricula->numero_cuotaspagadas_matricula = $matricula->numero_cuotaspagadas_matricula + 1;
            
            if($matricula->update()){
                $arr['res'] = 'ok';
                
                $pago->commit();
            }else{
                $arr['msg'] = 'Error al registrar el pago del valor de matricula';
                
                $pago->rollback();
            }
        }else{
            $arr['msg'] = 'Error al registrar el pago del valor de matricula';
            
            $pago->rollback();
        }
        
        exit(json_encode($arr));
    }
}
