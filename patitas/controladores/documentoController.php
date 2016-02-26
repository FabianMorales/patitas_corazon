<?php

class documentoController extends myController{
    public function index(){
        $categorias = CategoriaDoc::all();
        $idCat = myApp::getRequest()->getVar("id_cat");
        $usuario = JFactory::getUser();
        
        if (empty($idCat)){
            $categoria = CategoriaDoc::with(["documentos" => function($query) use ($usuario) {
                if (!$usuario->id){
                    $query->whereRaw("publicado = 'Y'")->whereRaw("visibilidad <> 'P'");
                }
                else{
                    $query->whereRaw("publicado = 'Y'");
                }
            }])->first();
        }
        else{
            $categoria = CategoriaDoc::where("id", $idCat)->with(["documentos" => function($query) use ($usuario) {                
                if (!$usuario->id){
                    $query->whereRaw("publicado = 'Y'")->whereRaw("visibilidad <> 'P'");
                }
                else{
                    $query->whereRaw("publicado = 'Y'");
                }
            }])->first();
        }
        
        $files = ["doc", "docx", "xls", "xlsx", "ppt", "pptx", "zip", "rar", "pdf"];
        
        return myView::render("documento.lista_documentos", ["categorias" => $categorias, "categoria" => $categoria, "files" => $files]);
    }
    
    public function descargar(){
        $idDoc = myApp::getRequest()->getVar("id");
        $documento = Documento::find($idDoc);
        
        if (!sizeof($documento)){
            return myApp::redirect('index.php?option=com_my_component&controller=documento', 'Documento no encontrado');
        }
        
        $archivo = myApp::pathDocumentos().DS.$documento->id.".".$documento->extension;
        
        if (!is_file($archivo)){
            return myApp::redirect('index.php?option=com_my_component&controller=documento', 'No se puede encontrar el archivo');
        }
        
        myApp::getFunciones()->descargarArchivo($archivo, $documento->nombre.".".$documento->extension);
    }
}