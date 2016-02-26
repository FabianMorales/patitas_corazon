<?php

class carritoController extends myController{
    function obtenerIdSesion(){
        $sesion = JFactory::getSession();
        $idSesion = $sesion->get("id_sesion");
               
        if (empty($idSesion)){
            $idSesion = uniqid();
            $sesion->set("id_sesion", $idSesion);            
        }
        return $idSesion;
    }

    function guardarIdSesion($id){
        $sesion = JFactory::getSession();
        $sesion->set("id_sesion", $id);
    }

    public function index(){
        $idSesion = $this->obtenerIdSesion();
        $carrito = Carrito::where("id_sesion", $idSesion)->with(["extension.producto.imagenes", "extension.talla", "extension.color"])->get();
        $totales = Carrito::totales($idSesion);
        $myCfg = new myConfig();
        return myView::render("carrito.index", ["urlImg" => myApp::urlImg(), "carrito" => $carrito, "totales" => $totales, "envio" => $myCfg->gastosEnvio]);
    }

    public function listarCarrito(){
        $idSesion = $this->obtenerIdSesion();
        $carrito = Carrito::where("id_sesion", $idSesion)->with(["extension.producto.imagenes", "extension.talla", "extension.color"])->get();
        $totales = Carrito::totales($idSesion);
        $myCfg = new myConfig();
        return myView::render("carrito.lista_items", ["urlImg" => myApp::urlImg(), "carrito" => $carrito, "totales" => $totales, "envio" => $myCfg->gastosEnvio]);
    }

    public function agregarProducto(){
        $idSesion = $this->obtenerIdSesion();
        $request = myApp::getRequest();
        $idProducto = $request->getVar("id_producto");
        $idExt = $request->getVar("id_ext");
        $cantidad = $request->getVar("cantidad", 0, "int");
        $ext = Extension::find($idExt);

        if (sizeof($ext)){
            $reg = Carrito::where("id_sesion", $idSesion)->where("id_ext", $idExt)->first();            
            if (!sizeof($reg)){
                $reg = new Carrito();
                $reg->id_sesion = $idSesion;
                $reg->id_ext = $idExt;
                $reg->id_referencia = $idProducto;
                $reg->cantidad = 0;
                $reg->fecha = date('Y-m-d H:i:s');

                $user = JFactory::getUser();
                if ($user->id){
                    $reg->id_usuario = $user->id;
                }
            }

            $reg->cantidad += (int)$cantidad;
            $reg->save();
        }

        return $this->mostrarTotalesModulo();
    }

    public function mostrarTotalesModulo(){
        $doc = myApp::getDocumento();
        $doc->addScript(JUri::root()."myCore/js/catalogo.js");
        $idSesion = $this->obtenerIdSesion();
        $carrito = Carrito::where("id_sesion", $idSesion)->with(["extension.producto.imagenes", "extension.talla", "extension.color"])->get();        
        $totales = Carrito::totales($idSesion);
        $myCfg = new myConfig();
        return myView::render("carrito.modulo", ["urlImg" => myApp::urlImg(), "carrito" => $carrito, "totales" => $totales, "envio" => $myCfg->gastosEnvio]);
    }
}