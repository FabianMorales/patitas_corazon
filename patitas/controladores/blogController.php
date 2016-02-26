<?php

class blogController extends myController{
    public function index(){
        return $this->mostrarListaArticulos();
    }
    
    public function estado(){
        phpinfo();
    }

    public function mostrarMenuBlog(){
        $db = myApp::getEloquent();
        $doc = $db::table('content')
                ->select($db::raw('extract(year from created) a'), $db::raw('extract(year_month from created) fecha_a'), $db::raw('extract(month from created) m'))
                ->where('catid', '>', '2')
                ->distinct()
                ->get();
        
        $a = array();
        foreach ($doc as $d){
            
            $y = sizeof($a[$d["a"]]) ? $a[$d["a"]] : array();
            $ym = sizeof($y[$d["m"]]) ? $y[$d["m"]] : $d["fecha_a"];
            
            //$ym[] = array("id" => $d["id"], "alias" => $d["alias"], "title" => $d["title"], "id_categoria" => $d["catid"]);
            $y[$d["m"]] = $ym;
            $a[$d["a"]] = $y;
        }
        
        return $a;
    }
    
    public function mostrarListaArticulos(){
        myApp::getDocumento()->establecerTitulo("Blog");
        $articulos = null;
        $meses = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        
        $idCat = myApp::getRequest()->getVar("id_categoria");
        $item = myApp::getRequest()->getVar("Itemid");
        $ym = myApp::getRequest()->getVar("ym");
        $menu = $this->mostrarMenuBlog();
        $cat = $idCat ? Categoria::find($idCat) : Categoria::where("alias", "<>", "uncategorised")->where('level', '>', 0)->orderBy('id')->first();
        
        if (sizeof($cat)){
            $articulos = $cat->articulos()->orderBy('created', 'desc');
        }
        else{
            $articulos = Articulo::where('catid', '>', '2')->orderBy('created', 'desc');
        }
        
        if ($ym){
            $articulos = $articulos->whereRaw('extract(year_month from created) = '. $ym)->paginate(10);
        }
        else{
            $articulos = $articulos->paginate(10);
        }
        
        myView::adicionarFuncion("decodificarJson", array($this, "decodificarJson"));
        
        $cInsta = myApp::getController("instagram");
        $instagram = $cInsta->sidebar("pandorascode", 6);
        
        return myView::render("blog.lista_art", ["articulos" => $articulos, "categoria" => $cat, "url" => JUri::root(), "Itemid" => $item, "meses" => $meses, "menu" => $menu, "instagram" => $instagram ]);
    }
    
    public function detalleArticulo(){
        myApp::getDocumento()->establecerTitulo("Blog");
        $id = myApp::getRequest()->getVar("id");
        $articulo = Articulo::find($id);
        
        if (!sizeof($articulo)){
            myApp::redirect("index.php", "Art&iacute;culo no encontrado");
        }
        
        $meses = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        
        $idCat = $articulo->catid;
        $item = myApp::getRequest()->getVar("Itemid");
        $menu = $this->mostrarMenuBlog();
        $cat = Categoria::find($idCat);
        
        myView::adicionarFuncion("decodificarJson", array($this, "decodificarJson"));
        
        $cInsta = myApp::getController("instagram");
        $instagram = $cInsta->sidebar("pandorascode", 6);
        
        return myView::render("blog.detalle", ["articulo" => $articulo, "categoria" => $cat, "url" => JUri::root(), "Itemid" => $item, "meses" => $meses, "menu" => $menu, "instagram" => $instagram ]);
    }    
    
    public function mostrarMenuCat(){
        $item = myApp::getRequest()->getVar("Itemid");
        
        $cat = Categoria::where("alias", "<>", "uncategorised")
            ->where('level', '>', 0)
            ->get();
        return myView::render("blog.menu_cat", ["categorias" => $cat, "Itemid" => $item]);
    }
    
    public function decodificarJson($json){
        return json_decode($json);
    }
}