<?php

Load::models('Alumno', 'Matricula', 'Alumnoprograma', 'Tipodocumento', 'Programa', 'Semestre', 'Formapago', 'Pais', 'Region', 'Localidad', 'Pago', 'Validacion', 'Pagovalidacion', 'Nota', 'Egresado', 'Egresadoprograma', 'Acta', 'Seguimientoegresado', 'Docente', 'Periododocente', 'Pagov', 'Materiaprograma');

class ManagementController extends AppController {
    public function administracion() {
        if(Auth::is_valid()){
            View::template('general_template');
            
            $this->usuario = Auth::get('nombre_usuario') . ' ' . Auth::get('apellido_usuario');
        }else{
            Router::redirect("/");
        }
    }
    
    public function matricula() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function matricular() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function consultamatricula() {
        if(Auth::is_valid()){
            View::template(NULL);
        }else{
            Router::redirect("/");
        }
    }
    
    public function formulariomatricula() {
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
        
        switch ($bandera) {
            case 1:
                /* Matricula normal de segundo o tercer semestre */
                
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
                
                break;
            case 2:
                /* Matricula convalidación */
                
                
                break;
        }
    }
    
    public function formularioacta() {
        View::template(NULL);
        
        $alumno = new Alumno();
        
        $tipo = Input::request('tipo');
        $documento = filter_var(Input::request('documento'), FILTER_SANITIZE_STRING);
        
        $this->tipo = $tipo;
        $this->documento = $documento;
        $this->datos = $alumno->cargarDatosAlumnoActa($documento);
        
        if($tipo == 2){
            $acta = new Acta();
            
            $this->datosacta = $acta->cargarDatosActaAlumno($documento);
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
    
    public function consultardatosmatriculaalumno() {
        View::template(NULL);
        
        $matricula = new Matricula();
        
        $documento = Input::request('documento');
        
        if($matricula->validarIdentificacion($documento)){
            $this->estado = 1;
            
            $this->datosmatricula = $matricula->cargarDatosConsultaMatricula($documento);
        }else{
            $this->estado = 0;
        }
    }
    
    public function consultarDatosAlumnoActa() {
        View::select(NULL, NULL);
        
        $acta = new Acta();
        $egresado = new Egresado();
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $documento = Input::request('documento');
        
        if($egresado->validarDocumentoAlumno($documento)){
            if($acta->validarActaAlumno($documento)){
                $arr['tipoerror'] = 2;
                $arr['msg'] = 'El número de documento del estudiante ya tiene un acta registrada en base de datos';
            }else{
                $arr['res'] = 'ok';
            }
        }else{
            $arr['tipoerror'] = 1;
            $arr['msg'] = 'El número de documento del estudiante no se encuentra registrado como egresado';
        }
        
        exit(json_encode($arr));
    }
    
    public function consultarDatosActaAlumno() {
        View::select(NULL, NULL);
        
        $acta = new Acta();
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $documento = Input::request('documento');
        
        if($acta->validarActaAlumno($documento)){
            $arr['res'] = 'ok';
        }else{
            $arr['msg'] = 'El número de documento del estudiante no tiene una acta de grado asociada';
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
            $semestre = filter_var(Input::request('matricula'), FILTER_SANITIZE_STRING);
            $sede = filter_var(Input::request('sedematricula'), FILTER_SANITIZE_STRING);
            $tipoDocumento = filter_var(Input::request('tipodocumento'), FILTER_SANITIZE_STRING);
            $documento = filter_var(Input::request('numerodocumento'), FILTER_SANITIZE_STRING);
            //$pais = filter_var(Input::request('paisE'), FILTER_SANITIZE_STRING);
            //$region = filter_var(Input::request('regionE'), FILTER_SANITIZE_STRING);
            $localidad = filter_var(Input::request('localidadE'), FILTER_SANITIZE_STRING);
            $nombre = filter_var(Input::request('nombre'), FILTER_SANITIZE_STRING);
            $apellido = filter_var(Input::request('apellido'), FILTER_SANITIZE_STRING);
            $fechaNacimiento = filter_var(Input::request('fechanacimiento'), FILTER_SANITIZE_STRING);
            //$paisNacimiento = filter_var(Input::request('paisN'), FILTER_SANITIZE_STRING);
            //$regionNacimiento = filter_var(Input::request('regionN'), FILTER_SANITIZE_STRING);
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
            //$codigoEstudiante = filter_var(Input::request('codigoestudiantil'), FILTER_SANITIZE_STRING);
            $numeroMatricula = filter_var(Input::request('numeromatricula'), FILTER_SANITIZE_STRING);
            $fechaMatricula = filter_var(Input::request('fechamatricula'), FILTER_SANITIZE_STRING);
            //$beca = filter_var(Input::request('beca'), FILTER_SANITIZE_STRING);
            $valorMatricula = filter_var(Input::request('valormatricula'), FILTER_SANITIZE_STRING);
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
                    $matricula->valor_matricula = $valorMatricula;
                    
                    if(is_null($numeroCuotas)){
                        $matricula->numero_cuotas_matricula = 1;
                        $matricula->numero_cuotaspagadas_matricula = '0';
                    }else{
                        $matricula->numero_cuotas_matricula = $numeroCuotas;
                        $matricula->numero_cuotaspagadas_matricula = '0';
                    }
                    
                    $matricula->id_sede = $sede;
                    $matricula->id_alumno = $alumno->id_alumno;
                    
                    if(is_null($semestre)){
                        $matricula->id_semestre = 1;
                    }else{
                        $matricula->id_semestre = $semestre;
                        $matricula->convalidacion = 1;
                    }
                    
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
            //$codigoEstudiante = filter_var(Input::request('codigoestudiantil'), FILTER_SANITIZE_STRING);
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
    
    public function pagos() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
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
                
                $date = new DateTime($datos[0]->fecha_matricula);
                $this->fechaMatricula = $date->format('Y-m-d');
                
                $this->numeroCuotas = $numerocuotas;
                $this->cuotasPagadas = $cuotaspagadas;
                $this->abreviaturaid = $datos[0]->abreviatura_tipodocumento;
                $this->id = $datos[0]->identificacion_alumno;
                $this->nombre = $datos[0]->nombre_alumno . ' ' . $datos[0]->apellido_alumno;
                $this->programa = $datos[0]->nombre_programa;
                $this->sede = $datos[0]->id_sede;
                $this->valorsemestreprograma = $datos[0]->valor_semestre_programa;
                $this->valorsemestre = $datos[0]->valor_matricula;
                $this->tipoPago = $datos[0]->formapago;
                
                if($numerocuotas === $cuotaspagadas){
                    $this->estadosaldo = 1;
                }else{
                    $this->estadosaldo = 0;
                }
            }else{
                $this->idMatricula = 0;
                $this->valorsemestreprograma = 0;
                $this->valorsemestre = 0;
            }
        }else{
            Router::redirect("/");
        }
    }
    
    public function pagovalidacion($idAlumno = 0) {
        if(Auth::is_valid()){
            View::template('general_template');
            
            $matricula = new Matricula();
            $validacion = new Validacion();
            
            $this->idAlumno = $idAlumno;
            
            if($idAlumno != 0) {
                $datos = $matricula->cargarDatosMatricula($idAlumno);
                
                $this->idMatricula = $datos[0]->id_matricula;
                $this->abreviaturaid = $datos[0]->abreviatura_tipodocumento;
                $this->id = $datos[0]->identificacion_alumno;
                $this->nombre = $datos[0]->nombre_alumno . ' ' . $datos[0]->apellido_alumno;
                $this->programa = $datos[0]->nombre_programa;
                
                //$this->numerovalidaciones = $validacion->cargarNumeroValidacionesAlumno($idAlumno);
                $this->validaciones = $validacion->cargarValidacionesAlumno($idAlumno);
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
        
        $numerorecibo = (int) $pago->cargarNumeroReciboPago()[0]->recibo;
        
        $pago->numero_recibo_pago = ($numerorecibo + 1);
        $pago->valor_pago = $valor;
        $pago->id_matricula = $idmatricula;
        $pago->id_mediopago = 1;
        
        if($pago->save()){
            $arr['pago'] = $pago->id_pago;
            
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
    
    public function registrarpagobeca() {
        View::template(NULL);
        
        $pago = new Pago();
        $matricula = new Matricula();
        $valor = filter_var(Input::request('valor'), FILTER_SANITIZE_STRING);
        $idmatricula = filter_var(Input::request('idMatricula'), FILTER_SANITIZE_STRING);
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $pago->begin();
        
        $numerorecibo = (int) $pago->cargarNumeroReciboPago()[0]->recibo;
        
        $pago->numero_recibo_pago = ($numerorecibo + 1);
        $pago->valor_pago = $valor;
        $pago->id_matricula = $idmatricula;
        $pago->id_mediopago = 3;
        
        if($pago->save()){
            $arr['pago'] = $pago->id_pago;
            
            $matricula->cargarDatosMatriculaActualizar($idmatricula);
            $matricula->valor_matricula = $valor;
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
        
        $numerorecibo = (int) $pago->cargarNumeroReciboPago()[0]->recibo;
        
        $pago->numero_recibo_pago = ($numerorecibo + 1);
        $pago->valor_pago = $valor;
        $pago->id_matricula = $idmatricula;
        $pago->id_mediopago = 1;
        
        if($pago->save()){
            $arr['pago'] = $pago->id_pago;
            
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
    
    public function registrarpagovalidacion() {
        View::template(NULL);
        
        $pago = new Pagovalidacion();
        $validacion = Input::request('validacion');
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $pago->begin();
        
        $pago->cargarPagoValidacion($validacion);
        $pago->estado_pagovalidacion = 2;
        $pago->valor_pagovalidacion = $pago->valor_pagovalidacion + 10000;
        
        if($pago->update()){
            $pagov = new Pagov();
            
            $numerorecibo = (int) $pagov->cargarNumeroReciboPago()[0]->recibo;
            
            $pagov->numero_recibo_pagov = ($numerorecibo + 1);
            $pagov->id_pagovalidacion = $pago->id_pagovalidacion;
            
            if($pagov->save()){
                $arr['res'] = 'ok';
                $arr['pagov'] = $pagov->id_pagov;
                
                $pago->commit();
            }else{
                $arr['msg'] = 'Error al registrar el pago de la validacion';
                
                $pago->rollback();
            }
        }else{
            $arr['msg'] = 'Error al registrar el pago de la validacion';
            
            $pago->rollback();
        }
        
        exit(json_encode($arr));
    }
    
    public function cerrarsemestre() {
        if(Auth::is_valid()){
            View::select(NULL, NULL);
            
            $matricula = new Matricula();
            
            $arr['res'] = 'fail';
            $arr['msg'] = '';
            
            $matricula->begin();
            
            if($matricula->update_all("id_estadomatricula = 2", "id_estadomatricula = 1")){
                $arr['res'] = 'ok';
                
                $matricula->commit();
            }else{
                $arr['msg'] = 'Error al registrar cierre de semestre';
                
                $matricula->rollback();
            }
            
            exit(json_encode($arr));
        }else{
            Router::redirect("/");
        }
    }
    
    public function egresados() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function registroegresados() {
        View::template(NULL);
            
        $matricula = new Matricula();
        
        $alumnos = $matricula->alumnos();
        $this->programas = $matricula->programas();
        $this->alumnos = $alumnos;
        $egresados = Array();
        
        foreach ($alumnos as $alumno) {
            $nota = new Nota();
            $validacion = new Validacion();
            
            $materias = $nota->cargarMateriasAlumno($alumno->id_alumno);
            $notas = $nota->cargarNotasMateriasAlumno($alumno->id_alumno);
            $validaciones = $validacion->cargarNotasValidacionesMateriasAlumno($alumno->id_alumno);
            
            foreach ($materias as $materia) {
                $definitiva = 0;
                
                foreach ($notas as $grade) {
                    if($materia->id_materia == $grade->id_materia){
                        switch ($grade->id_tiponota) {
                            case 1:
                                $definitiva += ($grade->valor_nota*0.3);
                                break;
                            case 2:
                                $definitiva += ($grade->valor_nota*0.3);
                                break;
                            case 3:
                                $definitiva += ($grade->valor_nota*0.4);
                                break;
                        }
                    }
                }
                
                if(round($definitiva, 1) > 3.5){
                    $egresados[] = array('id' => $alumno->id_alumno, 'materia' => $materia->id_materia, 'estado' => 1);
                }else{
                    $valor = 0;
                    
                    foreach ($validaciones as $validacion) {
                        if($materia->id_materia == $validacion->id_materia && $alumno->id_alumno == $validacion->id_alumno){
                            $valor = $validacion->valor_validacion;
                            
                            break;
                        }
                    }
                    
                    if($valor < 3.5){
                        $egresados[] = array('id' => $alumno->id_alumno, 'materia' => $materia->id_materia, 'estado' => 2);
                    }else{
                        $egresados[] = array('id' => $alumno->id_alumno, 'materia' => $materia->id_materia, 'estado' => 1);
                    }
                }
            }
        }
        
        $this->egresados = $egresados;
    }
    
    public function seguimientoegresados() {
        if(Auth::is_valid()){
            View::template('general_template');
            
            $programa = new Programa();
            
            $this->programas = $programa->programas();
        }else{
            Router::redirect("/");
        }
    }
    
    public function registraregresados() {
        if(Auth::is_valid()){
            View::select(NULL, NULL);
            
            $graduated = new Egresado();
            
            $egresados = json_decode(stripslashes(Input::request('egresados')));
            $banegresados = 1;
            
            $arr['res'] = 'fail';
            $arr['msg'] = '';
            
            $graduated->begin();
            
            foreach ($egresados as $e) {
                $alumno = new Alumno();
                $egresado = new Egresado();
                
                $noegresado = $alumno->cargarDatosAlumno($e->idalumno);
                
                $egresado->identificacion_egresado = $noegresado->identificacion_alumno;
                $egresado->lugar_expedicion_identificacion_egresado = $noegresado->lugar_expedicion_identificacion_alumno;
                $egresado->nombre_egresado = $noegresado->nombre_alumno;
                $egresado->apellido_egresado = $noegresado->apellido_alumno;
                $egresado->fecha_nacimiento_egresado = $noegresado->fecha_nacimiento_alumno;
                $egresado->lugar_nacimiento_egresado = $noegresado->lugar_nacimiento_alumno;
                $egresado->direccion_egresado = $noegresado->direccion_alumno;
                $egresado->telefono_egresado = $noegresado->telefono_alumno;
                $egresado->correo_electronico_egresado = $noegresado->correo_electronico_alumno;
                $egresado->contrasena_egresado = $noegresado->contrasena_alumno;
                $egresado->ocupacion_egresado = $noegresado->ocupacion_alumno;
                $egresado->empresa_egresado = $noegresado->empresa_alumno;
                $egresado->direccion_empresa_egresado = $noegresado->direccion_empresa_alumno;
                $egresado->telefono_empresa_egresado = $noegresado->telefono_empresa_alumno;
                
                if($egresado->save()){
                    $egresadoprograma = new Egresadoprograma();
                    
                    $egresadoprograma->id_egresado = $egresado->id_egresado;
                    $egresadoprograma->id_programa = $e->idprograma;
                    
                    if($egresadoprograma->save()){
                        $estudiante = new Alumno();
                        
                        $estudiante->cargarDatosAlumno($e->idalumno);
                        
                        $estudiante->id_estadoegresado = 1;
                        
                        if(!$estudiante->update()){
                            $banegresados = 0;
                        }
                    }else{
                        $banegresados = 0;
                    }
                }else{
                    $banegresados = 0;
                }
            }
            
            if($banegresados === 1){
                $arr['res'] = 'ok';
                
                $graduated->commit();
            }else{
                $arr['msg'] = 'El registro de egresados no fue exitoso. Intentar nuevamente';
                
                $graduated->rollback();
            }
            
            exit(json_encode($arr));
        }else{
            Router::redirect("/");
        }
    }
    
    public function cargaraniosegresadosprograma() {
        if(Auth::is_valid()){
            View::select(NULL, NULL);
            
            $acta = new Acta();
            
            $programa = filter_var(Input::request('programa'), FILTER_SANITIZE_STRING);
            
            echo json_encode($acta->cargarAniosEgresadosPrograma($programa));
        }else{
            Router::redirect("/");
        }
    }
    
    public function actas() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function registroacta() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function consultaacta() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function registraracta() {
        if(Auth::is_valid()){
            View::select(NULL, NULL);
            
            $acta = new Acta();
            
            $alumno = Input::request('alumno');
            $fechaacta = filter_var(Input::request('fechaacta'), FILTER_SANITIZE_STRING);
            $numacta = filter_var(Input::request('numeroacta'), FILTER_SANITIZE_STRING);
            $numlibro = filter_var(Input::request('numerolibro'), FILTER_SANITIZE_STRING);
            $numfolio = filter_var(Input::request('numerofolio'), FILTER_SANITIZE_STRING);
            $items = filter_var(Input::request('items'), FILTER_SANITIZE_STRING);
            $expedicionacta = filter_var(Input::request('expedicionacta'), FILTER_SANITIZE_STRING);
            
            $arr['res'] = 'fail';
            $arr['msg'] = '';
            
            $acta->begin();
            
            $acta->fecha_acta = $fechaacta;
            $acta->numero_acta = $numacta;
            $acta->libro_acta = $numlibro;
            $acta->folio_acta = $numfolio;
            $acta->item_acta = $items;
            $acta->lugar_expedicion_acta = $expedicionacta;
            $acta->id_alumno = $alumno;
            
            if($acta->save()){
                $arr['res'] = 'ok';
                
                $acta->commit();
            }else{
                $arr['msg'] = 'Ocurrio un error en el registro del acta. Intentar nuevamente';
                
                $acta->rollback();
            }
            
            exit(json_encode($arr));
        }else{
            Router::redirect("/");
        }
    }
    
    public function cargaregresados($page = 1) {
        View::template(NULL);
        
        $matricula = new Matricula();
        
        $programa = Input::request('programa');
        $anio = Input::request('anio');
        $periodo = Input::request('periodo');
        
        $this->programa = $programa;
        $this->anio = $anio;
        $this->periodo = $periodo;
        $this->egresados = $matricula->cargarEgresados($page, $programa, $anio, $periodo);
    }
    
    public function egresado($id) {
        if(Auth::is_valid()){
            View::template('general_template');
            
            $egresado = new Egresado();
            $seguimientoegresado = new Seguimientoegresado();
            
            $documento = $id;
            
            $this->documento = $documento;
            $this->datosegresado = $egresado->cargarDatosEgresado($documento);
            $this->seguimiento = $seguimientoegresado->cargarUltimoSeguimientoEgresado($documento);
        }else{
            Router::redirect("/");
        }
    }
    
    public function actulizardatosegresado() {
        if(Auth::is_valid()){
            View::select(NULL, NULL);
            
            $egresado = new Egresado();
            
            $idegresado = filter_var(Input::request('egresado'), FILTER_SANITIZE_STRING);
            $correo = filter_var(Input::request('correo'), FILTER_SANITIZE_STRING);
            $telefono = filter_var(Input::request('telefono'), FILTER_SANITIZE_STRING);
            $direccion = filter_var(Input::request('direccion'), FILTER_SANITIZE_STRING);
            $empresa = filter_var(Input::request('empresa'), FILTER_SANITIZE_STRING);
            $ocupacion = filter_var(Input::request('ocupacion'), FILTER_SANITIZE_STRING);
            $dirempresa = filter_var(Input::request('dirempresa'), FILTER_SANITIZE_STRING);
            $telempresa = filter_var(Input::request('telempresa'), FILTER_SANITIZE_STRING);
            
            $arr['res'] = 'fail';
            $arr['msg'] = '';
            
            $egresado->begin();
            
            $egresado->cargarDatosEgresadoActualizar($idegresado);
            $egresado->correo_electronico_egresado = $correo;
            $egresado->direccion_egresado = $direccion;
            $egresado->telefono_egresado = $telefono;
            $egresado->ocupacion_egresado = $ocupacion;
            $egresado->empresa_egresado = $empresa;
            $egresado->direccion_empresa_egresado = $dirempresa;
            $egresado->telefono_empresa_egresado = $telempresa;
            
            if($egresado->update()){
                $arr['res'] = 'ok';
                
                $egresado->commit();
            }else{
                $arr['msg'] = 'Ocurrio un error en actulización de los datos del egresado. Intentar nuevamente';
                
                $egresado->rollback();
            }
            
            exit(json_encode($arr));
        }else{
            Router::redirect("/");
        }
    }
    
    public function registrarseguimientoegresado() {
        if(Auth::is_valid()){
            View::select(NULL, NULL);
            
            $seguimientoegresado = new Seguimientoegresado();
            
            $egresado = filter_var(Input::request('egresado'), FILTER_SANITIZE_STRING);
            $observacion = filter_var(Input::request('observacion'), FILTER_SANITIZE_STRING);
            $llamada = filter_var(Input::request('llamada'), FILTER_SANITIZE_STRING);
            
            $arr['res'] = 'fail';
            $arr['msg'] = '';
            
            $seguimientoegresado->begin();
            
            if($llamada > 0){
                $seguimientoegresado->llamada_seguimientoegresado = 1;
            }else{
                $seguimientoegresado->llamada_seguimientoegresado = '0';
            }
            
            if(strlen($observacion) > 0){
                $seguimientoegresado->observacion_seguimientoegresado = $observacion;
            }
            
            $seguimientoegresado->id_egresado = $egresado;
            
            if($seguimientoegresado->save()){
                $arr['res'] = 'ok';
                
                $seguimientoegresado->commit();
            }else{
                $arr['msg'] = 'Ocurrio un error en el registro del seguimiento a egresado. Intentar nuevamente';
                
                $seguimientoegresado->rollback();
            }
            
            exit(json_encode($arr));
        }else{
            Router::redirect("/");
        }
    }
    
    public function docentes() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function consultardocumentodocente() {
        View::select(NULL, NULL);
        
        $docente = new Docente();
        
        $documentodocente = filter_var(Input::request('documentodocente'), FILTER_SANITIZE_STRING);
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        if($docente->validarDocumento($documentodocente)){
            $arr['res'] = 'ok';
        }
        
        exit(json_encode($arr));
    }
    
    public function periodosactivosdocente() {
        if(Auth::is_valid()){
            View::template(NULL);
            
            $docente = new Docente();
            $periododocente = new Periododocente();
            
            $documentodocente = filter_var(Input::request('documento'), FILTER_SANITIZE_STRING);
            
            $this->documentodocente = $documentodocente;
            $this->datosdocente = $docente->cargarDatosDocente($documentodocente);
            $this->periodosdocente = $periododocente->cargarPeriodosActivos($documentodocente);
        }else{
            Router::redirect("/");
        }
    }
    
    public function consultaactapromocion() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function consultardatosactas() {
        if(Auth::is_valid()){
            View::template(NULL);
            
            $acta = new Acta();
            
            $sedeacta = filter_var(Input::request('sede'), FILTER_SANITIZE_STRING);
            $numeroacta = filter_var(Input::request('acta'), FILTER_SANITIZE_STRING);
            
            $this->sede = $sedeacta;
            $this->acta = $numeroacta;
            $this->actas = $acta->cargarActasPromocion($sedeacta, $numeroacta);
        }else{
            Router::redirect("/");
        }
    }
    
    public function convalidacion() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function matricularconvalidacion() {
        if(Auth::is_valid()){
            View::template('general_template');
            
            
        }else{
            Router::redirect("/");
        }
    }
    
    public function registrarnotasconvalidacion() {
        if(Auth::is_valid()){
            View::template('general_template');
        }else{
            Router::redirect("/");
        }
    }
    
    public function cargardatosnotasconvalidacion() {
        View::template(NULL);
        
        $materiaprograma = new Materiaprograma();
        
        $programa = filter_var(Input::request('programa'), FILTER_SANITIZE_STRING);
        $semestre = filter_var(Input::request('semestre'), FILTER_SANITIZE_STRING);
        
        $this->documento = filter_var(Input::request('documento'), FILTER_SANITIZE_STRING);
        $this->datos1s = $materiaprograma->cargarDatosNotasConvalidacion($programa, 1);
        
        switch ($semestre) {
            case 2:
                $this->datos2s = $materiaprograma->cargarDatosNotasConvalidacion($programa, 2);
                
                break;
            case 3:
                $this->datos2s = $materiaprograma->cargarDatosNotasConvalidacion($programa, 2);
                $this->datos3s = $materiaprograma->cargarDatosNotasConvalidacion($programa, 3);
                
                break;
        }
    }
    
    public function consultardocumentoconvalidacion() {
        View::select(NULL, NULL);
        
        $matricula = new Matricula();
        
        $documento = filter_var(Input::request('documento'), FILTER_SANITIZE_STRING);
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        if($matricula->validarDocumentoConvalidacion($documento)){
            $arr['programa'] = $matricula->cargarProgramaMatriculaConvalidacion($documento);
            
            $arr['res'] = 'ok';
        }
        
        exit(json_encode($arr));
    }
    
    public function registrarnotascv() {
        View::select(NULL, NULL);
        
        $alumno = new Alumno();
        $grade = new Nota();
        
        $documento = Input::request('alumno');
        $notascv = json_decode(stripslashes(Input::request('notascv')));
        $bannotascv = 1;
        
        $arr['res'] = 'fail';
        $arr['msg'] = '';
        
        $grade->begin();
        
        $idalumno = $alumno->cargarIdAlumno($documento)[0]->id_alumno;
        
        foreach ($notascv as $ncv) {
            $nota = new Nota();
            
            $nota->valor_nota = $ncv->valor;
            $nota->id_tiponota = 4;
            $nota->id_alumno = $idalumno;
            $nota->id_materia = $ncv->materia;
            $nota->docente_nota = '0';
            $nota->faltas_nota = '0';

            if(!$nota->save()){
                $bannotascv = 0;
            }
        }
        
        if($bannotascv === 1){
            $arr['res'] = 'ok';
            
            $grade->commit();
        }else{
            $arr['msg'] = 'El registro de notas no fue exitoso. Intentar nuevamente';
            
            $grade->rollback();
        }
        
        exit(json_encode($arr));
    }
}
