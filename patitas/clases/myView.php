<?php

class myView {
    private static $twig;
    
    public static function boot(){
        Twig_Autoloader::register();
                
        $dirTemplates[] = dirname(__DIR__).DS."vistas".DS;
        
        $loaderTwig = new Twig_Loader_Filesystem($dirTemplates);
        myView::$twig = new Twig_Environment($loaderTwig, array("cache" => false));
        
        $filter = new Twig_SimpleFunction("trad", array(myView, "traducir"));        
        myView::$twig->addFunction($filter);
        
        $assetFn = new Twig_SimpleFunction("asset", array(myView, "asset"));        
        myView::$twig->addFunction($assetFn);
        
        $urlFn = new Twig_SimpleFunction("url", array(myView, "url"));        
        myView::$twig->addFunction($urlFn);        
        
        $lang = dirname(__DIR__)."/trad/".myApp::getLang().".php";
        if (is_file($lang)){
            include_once($lang);
        }
    }
    
    public static function render($vista, $vars = []){
        $doc = myApp::getDocumento();
        $vars["js_doc"] = $doc->getJs();
        $vars["css_doc"] = $doc->getCss();
        $vista = str_replace(".", "/", $vista).".twig";
        return myView::$twig->render($vista, $vars);
    }
	
    /*public static function renderPdf($vista, $vars, $archivo){
        $html = myView::render($vista, $vars);

        $estilosFou = file_get_contents(dirname(__DIR__)."/css/foundation/css/foundation.css");
        $estilosPdf = file_get_contents(dirname(__DIR__)."/css/pdf.css");
        
        $mpdf = new mPDF();
        $mpdf->WriteHTML($estilosFou, 1);
        $mpdf->WriteHTML($estilosPdf, 1);
        $mpdf->WriteHTML($html, 2);
        $mpdf->Output($archivo, "F");
    }*/
    
    public static function traducir($contenido, $seccion){ 
        $key = $seccion."_".md5($contenido);
        global $_trad;
        
        if (array_key_exists($key, $_trad)){
            return ($_trad[$key]);   
        }
        else{
            return $contenido;
        }
    }
    
    public static function asset($url){
        return myApp::getUrlRoot().$url;
    }
    
    public static function url($url){
        return myApp::getUrlRoot().$url;
    }
    
    public static function adicionarFuncion($alias, $funcion){
        $filter = new Twig_SimpleFilter($alias, $funcion);
        myView::$twig->addFilter($filter);
    }
}
