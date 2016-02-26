<?php

class homeController extends myController{
    public function index(){
        return myView::render("home.index");
    }
    
    public function educacion(){
        return myView::render("home.linea_educativa");
    }
    
    public function salud(){
        return myView::render("home.linea_salud");
    }
    
    public function variedad(){
        return myView::render("home.linea_variedad");
    }

    public function cocina(){
        return myView::render("home.linea_cocina");
    }
    
    public function literatura(){
        return myView::render("home.linea_literatura");
    }
    
    public function saludar($name, $apellido){
        return "Hola ".$name." ".$apellido." :)";
    }
    
    public function verificarCodigo($cod){
        $codigo = CodigoApp::where("codigo", $cod)->first();
        
        $ret = array();
        if (sizeof($codigo)){
            if ($codigo->estado == "V"){
                $ret["error"] = 0;
                $ret["valor"] = "OK";
                $ret["cd"] = $cod;
            }
            else{
                $ret["error"] = 1;
                $ret["valor"] = "Codigo no valido";
            }
        }
        else{
            $ret["error"] = 1;
            $ret["valor"] = "Codigo inexistente";
        }
        
        return "retorno(".json_encode($ret).")";
    }
}