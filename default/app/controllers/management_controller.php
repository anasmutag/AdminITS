<?php

Load::models('Tipodocumento', 'Programa', 'Semestre', 'Formapago', 'Pais', 'Region', 'Localidad');

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
            
            $tipodocumento = new Tipodocumento();
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
            $this->regiones = $region->regiones(82);
        }else{
            Router::redirect("/");
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
}
