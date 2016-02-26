<?php
    
use \Illuminate\Database\Capsule\Manager as Capsule;

class adminDocumentoController extends myAdminController{
    public function __construct() {
        ini_set('display_errors', 'On');
error_reporting(E_ALL);
        $doc = myApp::getDocumento();
        $doc->addScript(JUri::root()."media/jui/js/jquery.min.js");
        $doc->addEstilo(JUri::root()."media/jui/css/bootstrap.css");
        $doc->addEstilo(JUri::root()."myCore/css/foundation/css/foundation-grid.css");
        //bootstrap.min
    }
    
    public function index(){
        JToolbarHelper::title('Gestión de documentos');
        
        return $this->listarCategorias();
        /*$doc = myApp::getDocumento();
        $doc->incluirLibJs("fancybox", array("fancybox"));                
        return myView::render("admin.documento.index");*/
    }
    
    public function listarCategorias(){
        JToolbarHelper::title('Gestión de categorías');
        $categorias = CategoriaDoc::paginate(20);
        return myView::render("admin.documento.lista_categorias", ["categorias" => $categorias]);
    }
    
    public function formCategoria($categoria){
        JToolbarHelper::title('Gestión de categorias');        
        return myView::render("admin.documento.form_categoria", ["categoria" => $categoria]);
    }
    
    public function crearCategoria(){
        return $this->formCategoria(new CategoriaDoc());
    }
    
    public function editarCategoria(){
        $idCat = myApp::getRequest()->getVar("id");
        $categoria = CategoriaDoc::find($idCat);
        
        if (!sizeof($categoria)){
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarCategorias", "Categoría no encontrada");    
        }
        
        return $this->formCategoria($categoria);
    }
    
    public function guardarCategoria(){
        $request = myApp::getRequest();
        $idCat = myApp::getRequest()->getVar("id");
        $categoria = CategoriaDoc::find($idCat);
        
        if (!sizeof($categoria)){
            $categoria = new CategoriaDoc();
        }
        
        $categoria->fill($request->all());
        
        if ($categoria->save()){
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarCategorias", "Categoria guardada");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarCategorias", "No se pudo guardar la categoria");
        }
    }
    
    public function borrarCategoria(){
        $idCat = myApp::getRequest()->getVar("id");
        $categoria = CategoriaDoc::find($idCat);
        
        if (!sizeof($categoria)){
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarCategorias", "Categoría no encontrada");
        }        
        
        if ($categoria->delete()){
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarCategorias", "Categoría borrada");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarCategorias", "No se pudo borrar la cartegoría");
        }
    }
    
    public function listarDocumentos(){
        JToolbarHelper::title('Gestión de documentos');
        $documentos = Documento::paginate(20);
        return myView::render("admin.documento.lista_documentos", ["documentos" => $documentos]);
    }
    
    public function formDocumento($documento){
        JToolbarHelper::title('Gestión de documentos');        
        
        $categorias = CategoriaDoc::with(array('documentos' => function($query) use ($documento) {
            $query->where('id_documento', $documento->id);
        }))->get();

        return myView::render("admin.documento.form_documento", ["documento" => $documento, "categorias" => $categorias]);
    }
    
    public function crearDocumento(){
        return $this->formDocumento(new Documento());
    }
    
    public function editarDocumento(){
        $idDoc = myApp::getRequest()->getVar("id");
        $documento = Documento::find($idDoc);

        if (!sizeof($documento)){
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarDocumentos", "Documento no encontrado");    
        }        

        return $this->formDocumento($documento);
    }
    
    public function guardarDocumento(){
        $request = myApp::getRequest();
        $idDoc = myApp::getRequest()->getVar("id");
        $documento = Documento::find($idDoc);
        
        if (!sizeof($documento)){
            $documento = new Documento();
            $documento->fecha = date('Y-m-d');
        }
        
        $documento->fill($request->all());
        
        $f = $_FILES["archivo"];
        if (is_array($f) && is_uploaded_file($f["tmp_name"]) && !$f['error']){		    
		    $info = pathinfo($f['name']);
		    $documento->extension = strtolower($info["extension"]);
	    }
        
        if ($documento->save()){
            $categorias = $request->getVar("categorias", [], "ARRAY");            
            
            $documento->categorias()->detach();
            $documento->categorias()->attach($categorias);
            
            if (is_array($f) && is_uploaded_file($f["tmp_name"]) && !$f['error']){
			    $dirs = array(myApp::pathDocumentos());

			    $dir = "";
			    foreach ($dirs as $d){
				    $dir .=$d.DS;
				    if(!is_dir($dir)){
					    @mkdir($dir);
				    }
			    }
                $nombreArchivo = $documento->id.".".$documento->extension;
			    move_uploaded_file($f['tmp_name'], $dir.DS.$nombreArchivo);
		    }

            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarDocumentos", "Documento guardado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarDocumentos", "No se pudo guardar el documento");
        }
    }
    
    public function borrarDocumento(){
        $idDoc = myApp::getRequest()->getVar("id");
        $documento = Documento::find($idDoc);
        
        if (!sizeof($documento)){
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarDocumentos", "Documento no encontrado");
        }
        
        $nombreArchivo = myApp::pathDocumentos().DS.$documento->id.".".$documento->extension;
        if ($documento->delete()){
            @unlink($nombreArchivo);
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarDocumentos", "Documento borrado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminDocumento&task=listarDocumentos", "No se pudo borrar el documento");
        }
    }
}
