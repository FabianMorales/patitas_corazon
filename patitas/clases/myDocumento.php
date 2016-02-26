<?php
/*
	Héctor Fabián Morales Ramírez
	Tecnólogo en Ingeniería de Sistemas
	Enero 2011
*/
class myDocumento{
    var $archivosJs;
    var $archivosCss;
    var $tituloWeb;
    
    public function myDocumento(){
        $this->archivosJs = [];
        $this->archivosCss = [];
        //$this->archivosCss[] = "myCore/css/my.css";
    }
    
    public function getJs(){
        return $this->archivosJs;
    }
    
    public function getCss(){
        return $this->archivosCss;
    }
    
    public function establecerTitulo($titulo){
        $this->tituloWeb = $titulo;
    }

    public function addScript($url){
        if (!in_array($url, $this->archivosJs)){
            $this->archivosJs[] = $url;
        }
    }

    public function addScripts($array_url){        
        foreach ($array_url as $url){
            $this->addScript($url);
        }
    }

    public function addEstilo($url){
        if (!in_array($url, $this->archivosJs)){
            $this->archivosCss[] = $url;
        }
    }

    public function addEstilos($array_url){
        foreach ($array_url as $url){
            $this->addEstilo($url);
        }
    }

    public function incluirJQuery($version="1.10.2"){        
        $this->addScript('myCore/js/jquery/jquery-'.$version.'.js');
        //$document->addScriptDeclaration ('jQuery.noConflict();');
    }

    public function incluirLibJs($nombre, $css=null){        
        $this->addScript('myCore/js/'.$nombre.'/'.$nombre.'.js');        
        if (sizeof($css)){
            foreach ($css as $c){
                $this->addEstilo('myCore/js/'.$nombre.'/'.$c.'.css');
            }
        }
    }
}