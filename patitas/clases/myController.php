<?php

abstract class myController {

    public function ejecutar($tarea = "index", $args = []) {
        if (!$tarea) {
            $tarea = "index";
        }

        if (method_exists($this, $tarea)) {
            //echo $this->$tarea();
            $func = [$this, $tarea];
            echo call_user_func_array($func, $args);
        } else {
            //myApp::mostrarMensaje("Opción no válida", "error");
            echo "Opción no válida";
        }
    }

    public static function _($path) {
        list($controlador, $tarea) = explode("@", $path);
        $c = myApp::getController($controlador);
        return function() use ($c, $tarea) {
            echo $c->ejecutar($tarea, func_get_args());
        };
    }

    public abstract function index();
}
