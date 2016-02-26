<?php

abstract class myAdminController extends myController{
    public function ejecutar($tarea = "index", $args = []){
        $usuario = JFactory::getUser();
        $grupos = JUserHelper::getUserGroups($usuario->id);
        
        if (!(in_array(7, $grupos) || in_array(8, $grupos))){
            myApp::mostrarMensaje("Acceso denegado", "error");
            return "";
        }
        
        $app = JFactory::getApplication();
        if (!$app->isAdmin()){
            myApp::mostrarMensaje("Esta sección debe accederse desde el administrador", "error");
            return "";
        }
        
        parent::ejecutar($tarea, $args);
    }
    
    public function index() { }
}

