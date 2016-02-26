<?php

class instagramController extends myController{
    public function index($usuario="", $limite=0){
        if (empty($usuario)){
            $usuario = myApp::getRequest()->getVar("usuario");
        }
        
        $idUsuario = $this->obtenerIdUsuario($usuario);
        $items = $this->obtenerActividadReciente($idUsuario, $limite);
        
        return myView::render("instagram.actividad_reciente", array("items" => $items));
    }
    
    public function sidebar($usuario="", $limite=0){
        if (empty($usuario)){
            $usuario = myApp::getRequest()->getVar("usuario");
        }
        
        $idUsuario = $this->obtenerIdUsuario($usuario);
        $items = $this->obtenerActividadReciente($idUsuario, $limite);
        
        return myView::render("instagram.sidebar", array("items" => $items));
    }
    
    private function obtenerJson($url){
        $ch = curl_init(); 
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	$result = curl_exec ($ch);	
    	curl_close ($ch);    	
    	return json_decode($result);
    }
    
    private function obtenerIdUsuario($usuario){
        if (empty($usuario)){
            $usuario = myApp::getRequest()->getVar("usuario");
        }
        
        $cfg = new myConfig();
        $url = "https://api.instagram.com/v1/users/search?q=".$usuario."&client_id=".$cfg->idClienteInstagram;
        $usuario = '';
        $json = $this->obtenerJson($url);
        if (sizeof($json)){
            if (sizeof($json->data[0])){
                $usuario = $json->data[0]->id;
            }
        }
        
        return $usuario;
    }
    
    public function obtenerActividadReciente($idUsuario, $limite){
        $cfg = new myConfig();
        $url = "https://api.instagram.com/v1/users/".$idUsuario."/media/recent/?client_id=".$cfg->idClienteInstagram;
        
        if ($limite){
            $url .= "&count=".$limite;
        }
        
        $items = array();
        $json = $this->obtenerJson($url);
        
        if (sizeof($json)){
            $data = $json->data;
    		foreach ($data as $d){
                $item = array("img" => $d->images->thumbnail->url, "link" => $d->link);
    			$items[] = $item;
    		}
        }
        
        return $items;
    }
}