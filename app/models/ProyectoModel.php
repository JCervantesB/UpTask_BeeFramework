<?php

class ProyectoModel extends Model {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'fecha', 'propietarioId'];

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? null;
    }

    public function validarProyecto() {
        if(!$this->proyecto) {
            self::$alertas['error'][] = 'El Nombre del Proyecto es Obligatorio';
        }

        return self::$alertas;
    }

    
}