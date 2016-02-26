<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class adminWidgetController extends myAdminController{
    public function __construct() {
        $doc = myApp::getDocumento();
        $doc->addScript(JUri::root()."media/jui/js/jquery.min.js");
        $doc->addEstilo(JUri::root()."media/jui/css/bootstrap.css");
        $doc->incluirLibJs("fancybox", array("fancybox"));
        $doc->addScript(JUri::root()."myCore/js/admin.js");
    }
    
    public function obtenerTiposWidgets(){
        return ["slider" => "Slider", "mapa" => "Mapa", "galeria" => "Galeria", "red" => "Red Social"];
    }
    
    public function index(){
        JToolbarHelper::title('Gestión de widgets');
        $widgets = Widget::paginate(20);
        return myView::render("admin.widget.lista_widgets", ["widgets" => $widgets, "tipos" => $this->obtenerTiposWidgets()]);
    }
    
    public function listarTiposWidgets(){
        $tipos = $this->obtenerTiposWidgets();
        return myView::render("admin.widget.lista_tipos_widget", ["tipos" => $tipos]);
    }
    
    public function formWidget($widget){
        if (!sizeof($widget)){
            $widget = new Widget();
        }
        
        if (empty($widget->tipo)){
            $widget->tipo = myApp::getRequest()->getVar("tipo");
        }
        
        $params = json_decode($widget->config);
        return myView::render("admin.widget.".$widget->tipo.".form_".$widget->tipo, ["widget" => $widget, "params" => $params, "tipos" => $this->obtenerTiposWidgets(), "urlImg" => myApp::urlImg()]);
    }
    
    public function crearWidget(){
        $widget = new Widget();
        return $this->formWidget($widget);
    }
    
    public function editarWidget(){
        $id = (int)myApp::getRequest()->getVar("id");
        $widget = Widget::find($id);
        return $this->formWidget($widget);
    }
    
    public function guardarWidget(){
        $req = myApp::getRequest();
        $id = (int)$req->getVar("id");
        $tipo = $req->getVar("tipo");
        $widget = Widget::find($id);
        if (!sizeof($widget)){
            $widget = new Widget();
            $widget->tipo = $tipo;
        }
        
        $widget->fill($req->get());
        $params = $req->getVar("params", [], "ARRAY");
        $widget->config = json_encode($params);
        if ($widget->save()){
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$widget->id, "Widget creado exitosamente");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$id, "No se pudo crear el widget");
        }
    }
    
    public function listarPuntosMapa(){
        $puntos = PuntoMapa::paginate(20);
        return myView::render("admin.mapa.lista_puntos", ["puntos" => $puntos, "urlImg" => myApp::urlImg()]);
    }
    
    public function formPuntoMapa($punto){        
        if (!sizeof($punto)){            
            $punto = new PuntoMapa();            
        }
        
        if (empty($punto->id_widget)){
            $idWidget = myApp::getRequest()->getVar("id_widget");
            $punto->id_widget = $idWidget;
        }
        
        return myView::render("admin.widget.mapa.form_punto", ["punto" => $punto, "urlImg" => myApp::urlImg()]);
    }
    
    public function crearPuntoMapa(){
        $punto = new PuntoMapa();        
        return $this->formPuntoMapa($punto);
    }
    
    public function editarPuntoMapa(){
        $id = (int)myApp::getRequest()->getVar("id");
        $punto = PuntoMapa::find($id);
        return $this->formPuntoMapa($punto);
    }
    
    public function guardarPuntoMapa(){
        $request = myApp::getRequest();
        $idPunto = $request->getVar("id");
        $punto = PuntoMapa::find($idPunto);
        
        if (!sizeof($punto)){
            $punto = new PuntoMapa();
        }
        
        $punto->fill($request->all());
        if ($punto->centro == "Y"){
            Capsule::table(PuntoMapa::getTableName())->update(array('centro' => 'N'));
        }
        
        if ($punto->save()){
            $f = $_FILES['imagen'];
            if (is_array($f) && is_uploaded_file($f["tmp_name"]) && !$f['error']){
			    $dirs = array(myApp::pathImg(), "widgets", "mapa", $punto->id_widget);

			    $dir = "";
			    foreach ($dirs as $d){
				    $dir .=$d.DS;
				    if(!is_dir($dir)){
					    @mkdir($dir);
				    }
			    }

			    if (!is_dir($dir.DS."thumb")){
				    @mkdir($dir.DS."thumb");
			    }

			    $info = pathinfo($f['name']);
			    $nombreArchivo = $punto->id.".".$info["extension"];
			    move_uploaded_file($f['tmp_name'], $dir.DS.$nombreArchivo);			    
			    $func = myApp::getFunciones();
			    if (is_file($dir.DS.$nombreArchivo)){
				    $func->crearThumb($nombreArchivo, $dir, $dir.DS."thumb", 48, 48);
			    }
                
                $punto->imagen = $nombreArchivo;
                $punto->save();
		    }
            
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$punto->id_widget, "Punto guardado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$punto->id_widget, "No se pudo guardar el punto");
        }
    }
    
    public function borrarPuntoMapa(){
        $request = myApp::getRequest();
        $idPunto = $request->getVar("id");
        $punto = PuntoMapa::find($idPunto);
        
        if (!sizeof($punto)){
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$punto->id_widget, "Punto no encontrado");
        }        
        
        if ($punto->delete()){
            $dirs = array(myApp::pathImg(), "widgets", "mapa", $punto->id_widget);
            $dir = implode($dirs, DS);
            $nombreArchivo = $punto->imagen;
    	    @unlink($dir.DS.$nombreArchivo);
    	    @unlink($dir.DS."thumb".DS.$nombreArchivo);
            
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$punto->id_widget, "Punto eliminado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$punto->id_widget, "No se pudo eliminar el punto");
        }
    }
    
    public function quitarImgPuntoMapa(){
        $request = myApp::getRequest();
        $idPunto = $request->getVar("id");
        $punto = PuntoMapa::find($idPunto);
        
        if (!sizeof($punto)){
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$punto->id_widget, "Punto no encontrado");
        }
        
        $dirs = array(myApp::pathImg(), "widgets", "mapa", $punto->id_widget);
        $dir = implode($dirs, DS);
        $nombreArchivo = $punto->imagen;
	    @unlink($dir.DS.$nombreArchivo);
	    @unlink($dir.DS."thumb".DS.$nombreArchivo);
	    
	    $punto->imagen = "";
        $punto->save();
        
        myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$punto->id_widget, "Imagen removida");
    }
    
    public function listarItemsSlider(){
        $items = ItemSlider::paginate(20);
        return myView::render("admin.slider.lista_items", ["items" => $items, "urlImg" => myApp::urlImg()]);
    }
    
    public function formItemSlider($item){        
        if (!sizeof($item)){            
            $item = new ItemSlider();            
        }
        
        if (empty($item->id_widget)){
            $idWidget = myApp::getRequest()->getVar("id_widget");
            $item->id_widget = $idWidget;
        }
        
        return myView::render("admin.widget.slider.form_item", ["item" => $item, "urlImg" => myApp::urlImg()]);
    }
    
    public function crearItemSlider(){
        $item = new ItemSlider();        
        return $this->formItemSlider($item);
    }
    
    public function editarItemSlider(){
        $id = (int)myApp::getRequest()->getVar("id");
        $item = ItemSlider::find($id);
        return $this->formItemSlider($item);
    }
    
    public function guardarItemSlider(){
        $request = myApp::getRequest();
        $idItem = $request->getVar("id");
        $item = ItemSlider::find($idItem);
        
        if (!sizeof($item)){
            $item = new ItemSlider();
        }
        
        $item->fill($request->all());        
        
        if ($item->save()){
            $f = $_FILES['imagen'];
            if (is_array($f) && is_uploaded_file($f["tmp_name"]) && !$f['error']){
			    $dirs = array(myApp::pathImg(), "widgets", "slider", $item->id_widget);

			    $dir = "";
			    foreach ($dirs as $d){
				    $dir .=$d.DS;
				    if(!is_dir($dir)){
					    @mkdir($dir);
				    }
			    }

			    if (!is_dir($dir.DS."thumb")){
				    @mkdir($dir.DS."thumb");
			    }

			    $info = pathinfo($f['name']);
			    $nombreArchivo = $item->id.".".$info["extension"];
			    move_uploaded_file($f['tmp_name'], $dir.DS.$nombreArchivo);			    
			    $func = myApp::getFunciones();
			    if (is_file($dir.DS.$nombreArchivo)){
				    $func->crearThumb($nombreArchivo, $dir, $dir.DS."thumb", 48, 48);
			    }
                
                $item->imagen = $nombreArchivo;
                $item->save();
		    }
            
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$item->id_widget, "Item guardado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$item->id_widget, "No se pudo guardar el item");
        }
    }
    
    public function borrarItemSlider(){
        $request = myApp::getRequest();
        $idItem = $request->getVar("id");
        $item = ItemSlider::find($idItem);
        
        if (!sizeof($item)){
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$item->id_widget, "Item no encontrado");
        }        
        
        if ($item->delete()){
            $dirs = array(myApp::pathImg(), "widgets", "slider", $item->id_widget);
            $dir = implode($dirs, DS);
            $nombreArchivo = $item->imagen;
    	    @unlink($dir.DS.$nombreArchivo);
    	    @unlink($dir.DS."thumb".DS.$nombreArchivo);
            
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$item->id_widget, "Item eliminado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminWidget&task=editarWidget&id=".$item->id_widget, "No se pudo eliminar el item");
        }
    }
}
