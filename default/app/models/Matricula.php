<?php

class Matricula extends ActiveRecord {
    public function cargarDatosMatriculaActualizar($id) {
        return $this->find_first("conditions: id_matricula = $id");
    }
    
    public function cargarDatosMatricula($id) {
        return $this->find("columns: matricula.id_matricula,numero_matricula,fecha_matricula,valor_matricula,numero_cuotas_matricula,numero_cuotaspagadas_matricula,id_sede,abreviatura_tipodocumento,identificacion_alumno,nombre_alumno,apellido_alumno,nombre_programa,valor_semestre_programa,nombre_semestre,formapago.id_formapago as formapago,nombre_formapago",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join tipodocumento on alumno.id_tipodocumento = tipodocumento.id_tipodocumento
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa
                    join semestre on matricula.id_semestre = semestre.id_semestre
                    join formapago on matricula.id_formapago = formapago.id_formapago",
                "conditions: alumno.identificacion_alumno = $id",
                "order: numero_matricula desc limit 1");
    }
    
    public function cargarDatosConsultaMatricula($id) {
        return $this->find("columns: alumno.identificacion_alumno,alumno.nombre_alumno,alumno.apellido_alumno,nombre_sede,nombre_semestre,nombre_estadomatricula,programa.nombre_programa",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa
                    join sede on matricula.id_sede = sede.id_sede
                    join semestre on matricula.id_semestre = semestre.id_semestre
                    join estadomatricula on matricula.id_estadomatricula = estadomatricula.id_estadomatricula",
                "conditions: identificacion_alumno = $id",
                "order: id_matricula desc limit 1");
    }
    
    public function cargarDatosMatriculaInactiva($id) {
        return $this->find("columns: matricula.*,alumno.*,programa.*",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: matricula.id_estadomatricula = 2 and alumno.identificacion_alumno = $id",
                "order: id_semestre desc limit 1");
    }
    
    public function validarIdentificacion($id) {
        if ($this->find("columns: count(*) as resultado",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno",
                "conditions: identificacion_alumno = $id")[0]->resultado != 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function ultimoNumero() {
        return $this->find("columns: numero_matricula as resultado", "order: numero_matricula desc limit 1");
    }
    
    public function alumnos() {
        return $this->find("columns: matricula.id_alumno,nombre_alumno,apellido_alumno,programa.id_programa",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: id_semestre = 3
                    and id_estadomatricula = 2
                    and id_estadoegresado = 2",
                "order: apellido_alumno");
    }
    
    public function programas() {
        return $this->find("columns: programa.id_programa,nombre_programa",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: id_semestre = 3
                    and id_estadomatricula = 2
                    and id_estadoegresado = 2
                    group by programa.id_programa",
                "order: programa.id_programa");
    }
    
    public function cargarEgresados($page = 1, $programa, $anio, $periodo) {
        /*return $this->paginate_by_sql("select id_alumno,valor_nota"
                . " from nota",
                "page: $page", "per_page: 10");*/
        
        /*return $this->paginate_by_sql("select alumnoprograma.id_programa,alumno.id_alumno,alumno.identificacion_alumno,alumno.nombre_alumno,alumno.apellido_alumno"
                . " from acta join alumno on acta.id_alumno = alumno.id_alumno"
                    . " join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno"
                . " where id_programa = $programa"
                    . " and year(fecha_acta) = $anio"
                . " order by year(fecha_acta) and alumno.apellido_alumno",
                "page: $page", "per_page: 10");*/
        
        return $this->paginate_by_sql("select alumnoprograma.id_programa,alumno.id_alumno,alumno.identificacion_alumno,alumno.nombre_alumno,alumno.apellido_alumno"
                . " from matricula join alumno on matricula.id_alumno = alumno.id_alumno"
                    . " join acta on alumno.id_alumno = acta.id_alumno"
                    . " join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno"
                . " where id_programa = $programa"
                    . " and year(fecha_acta) = $anio"
                    . " and id_semestre = 3"
                    . " and id_periodo = $periodo"
                . " order by id_semestre desc",
                "page: $page", "per_page: 10");
    }
    
    public function validarDocumentoConvalidacion($documento) {
        if ($this->exists("id_alumno = (select id_alumno from alumno where identificacion_alumno = $documento) and convalidacion_matricula = 1")) {
            return true;
        } else {
            return false;
        }
    }
    
    public function cargarProgramaMatriculaConvalidacion($documento) {
        return $this->find("columns: programa.id_programa,id_semestre",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa",
                "conditions: identificacion_alumno = $documento
                    and convalidacion_matricula = 1");
    }
    
    public function cargarAlumnosMateria($sede, $programa, $materia) {
        return $this->find("columns: alumno.id_alumno,alumno.identificacion_alumno,alumno.nombre_alumno,alumno.apellido_alumno,materia.id_materia",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa
                    join materiaprograma on programa.id_programa = materiaprograma.id_programa
                    join materia on materiaprograma.id_materia = materia.id_materia",
                "conditions: matricula.id_sede = $sede
                    and programa.id_programa = $programa
                    and materia.id_materia = $materia
                    and matricula.id_estadomatricula = 1
                    and matricula.id_semestre = materiaprograma.semestre",
                "order: apellido_alumno");
    }
    
    public function cargarAlumnosMateriaPerdida($sede, $programa, $materia) {
        return $this->find("columns: alumno.id_alumno,alumno.identificacion_alumno,alumno.nombre_alumno,alumno.apellido_alumno,materia.id_materia",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa
                    join materiaprograma on programa.id_programa = materiaprograma.id_programa
                    join materia on materiaprograma.id_materia = materia.id_materia",
                "conditions: matricula.id_sede = $sede
                    and programa.id_programa = $programa
                    and materia.id_materia = $materia
                    and matricula.id_estadomatricula = 2
                    and matricula.id_semestre = materiaprograma.semestre",
                "order: apellido_alumno");
    }
    
    public function cargarNumeroAlumnosSemestre($sede, $materia) {
        return $this->find("columns: count(*) as resultado",
                "join: join alumno on matricula.id_alumno = alumno.id_alumno
                    join alumnoprograma on alumno.id_alumno = alumnoprograma.id_alumno
                    join programa on alumnoprograma.id_programa = programa.id_programa
                    join materiaprograma on programa.id_programa = materiaprograma.id_programa",
                "conditions: id_estadomatricula = 1
                    and id_semestre = (
                        select semestre
                        from materiaprograma
                        where id_materia = $materia
                    )
                    and id_sede = $sede
                    and materiaprograma.id_materia = $materia");
    }
}
