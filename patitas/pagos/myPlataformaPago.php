<?php
/*
    Héctor Fabián Morales Ramírez
    Tecnólogo en Ingeniería de Sistemas
    Enero 2011
*/
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

abstract class myPlataformaPago{
    var $tmpl;
    var $componente;
    protected $twig;
    protected $tmplVars;

    function __construct(){
        $this->componente = JRequest::getVar("option");
        $tmpl_base = dirname(__FILE__).DS.'tmpl'.DS;

        $loaderTwig = new Twig_Loader_Filesystem($tmpl_base);
        $this->twig = new Twig_Environment($loaderTwig, array("cache" => false));
    }
    
    public static function cargarPlataforma($clase){
        $archivoClase = dirname(__FILE__).DS.$clase.".php";        
        if (is_file($archivoClase)){            
            require_once($archivoClase);
            if (class_exists($clase)){
                return new $clase();
            }
        }
    }
    
    public function render($vista){
        $this->twig->display($vista, $this->tmplVars);
    }

    public function renderStr($vista){
        return $this->twig->render($vista, $this->tmplVars);
    }
     
    public abstract function realizarPago($pedido);
    public abstract function respuestaPago($get);
    public abstract function confirmacionPago($post);
}
?>