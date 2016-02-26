<?php

class widgetController extends myController{
    public function index(){
        $id = myApp::getRequest()->getVar("id");
        return $this->mostrarWidget($id);
    }
    
    public function mostrarWidget($id=""){
        if (empty($id)){
            $id = myApp::getRequest()->getVar("id");
        }
        
        $widget = Widget::find($id);
        
        if (!sizeof($widget)){
            return "Widget no encontrado";
        }
        
        $params = json_decode($widget->config);
        
        $doc = myApp::getDocumento();
        //$doc->addScript(JUri::root()."media/jui/js/jquery.min.js");
        switch ($widget->tipo){
            case "slider":
                $doc->addScript(JUri::root()."myCore/js/lightSlider/jquery.lightSlider.js");
                $doc->addEstilo(JUri::root()."myCore/js/lightSlider/lightSlider.css");                
                return myView::render("widget.slider", ["widget" => $widget, "params" => $params, "urlImg" => myApp::urlImg()]);
                break;
            case "mapa":
                $doc->addScript("http://maps.google.com/maps/api/js?sensor=false");
                return myView::render("widget.mapa", ["widget" => $widget, "params" => $params, "urlImg" => myApp::urlImg()]);
                break;
            default:
                return "El widget tiene un tipo no valido";
        }
    }
}