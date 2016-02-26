<?php

class catalogoController extends myController{
	function index(){
        return $this->listarProductos();
	}

	/*function menuCategorias($idCategoria=0, $tarea=""){    
		$principales = $idCategoria ? false : true;
		$idCategoriaParam = (int)$this->request->getVar("idCategoria");
		$catParam = $this->modelo->getCategoria($idCategoriaParam);
		$this->tmplVars["task"] = $tarea;
		$this->tmplVars["display"] = !$principales && $idCategoria != $idCategoriaParam && $idCategoria != $catParam["id_cat"] ? "style=\"display: none\"" : ""; 
		$this->tmplVars["nodo"] = $principales ? "nodoPadre" : "nodoHijo";		
		$this->tmplVars["listaCategorias"] = $this->modelo->getListaCategorias($principales, $idCategoria);
		return $this->renderStr("menuCategoria");
	}*/

    function listarProductos(){
        $request = myApp::getRequest();
        $idCategoria = $request->getVar("id_cat", 0, "int");
        $productos = [];
        $categoria = null;

        if (!empty($idCategoria)){
            $categoria = CategoriaRef::where("id", $idCategoria)->with(["productos.extensiones.color", "productos.extensiones.talla", "productos.atributosRef.atributo", "productos.imagenes"])->first();
            if (sizeof($categoria)){
                $productos = $categoria->productos;
            }
        }

        if (!sizeof($productos)){
            $productos = Producto::with(["extensiones.color", "extensiones.talla", "atributosRef.atributo", "imagenes"])->get();
        }

        return myView::render("catalogo.lista_productos", ["productos" => $productos, "urlImg" => myApp::urlImg(), "categoria" => $categoria]);        
    }
        
    function mostrarProducto(){
        $doc = myApp::getDocumento();
        //$doc->addScript(JUri::root()."media/jui/js/jquery.min.js");
        $doc->incluirLibJs("featherlight", ["featherlight"]);
        $doc->addScript(JUri::root()."myCore/js/catalogo.js");
        $idProd = myApp::getRequest()->getVar("id", 0, "int");
        $producto = Producto::where("id", $idProd)->with(["extensiones.color", "extensiones.talla", "atributosRef.atributo", "imagenes"])->first();

        if (sizeof($producto)){                        
		    /*$listaReferencias = array();
            if ($ref["tipo"] == "N"){
                $listaReferencias = array($ref);
            }
            else{
                $listaReferencias = $this->modelo->getListaConjuntoRef($idReferencia);
            }*/

            return myView::render("catalogo.detalle_producto", ["producto" => $producto, "urlImg" => myApp::urlImg()]);        
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=catalog", "Producto no encontrado");
        }
    }
}
?>