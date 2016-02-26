<?php
    
use \Illuminate\Database\Capsule\Manager as Capsule;

class adminCatalogoController extends myAdminController{
    public function __construct() {
        $doc = myApp::getDocumento();
        $doc->addScript(JUri::root()."media/jui/js/jquery.min.js");
        $doc->addEstilo(JUri::root()."media/jui/css/bootstrap.css");
        $doc->addScript(JUri::root()."myCore/js/jquery-ui/jquery-ui.js");
        $doc->addScript(JUri::root()."myCore/js/plupload/plupload.full.min.js");
        $doc->addScript(JUri::root()."myCore/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js");
        $doc->addScript(JUri::root()."myCore/js/plupload/jquery.plupload.queue/jquery.plupload.queue.min.js");
        $doc->addScript(JUri::root()."myCore/js/plupload/i18n/es.js");
        $doc->addScript(JUri::root()."myCore/js/my.js");
        $doc->addEstilo(JUri::root()."myCore/css/foundation/css/foundation-grid.css");
        $doc->addEstilo(JUri::root()."myCore/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css");
        $doc->addEstilo(JUri::root()."myCore/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css");
        //bootstrap.min
    }
    
    public function index(){
        JToolbarHelper::title('Gestión catálogo');
        
        $doc = myApp::getDocumento();
        $doc->incluirLibJs("fancybox", array("fancybox"));                
        return myView::render("admin.catalogo.index");
    }

    public function mostrarConsola(){
        /*$archivo = JPATH_ROOT.'/myCore/sql/estructura.sql';
        Capsule::unprepared(file_get_contents($archivo));*/
        //$c = Capsule::table(Capsule::raw("information_schema.tables"))->whereRaw(Capsule::raw("table_schema = DATABASE() and table_name like '%_my_%'"))->get();
        //print_r($c);
        return myView::render("admin.catalogo.consola");
    }

    public function procesarConsola(){
         Capsule::unprepared(myApp::getRequest()->getVar("sql"));
         if (Capsule::unprepared(file_get_contents($archivo)) == true){
             myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=mostrarConsola", "Consulta ejecutada");
         }
         else{
             myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=mostrarConsola", "Consulta no ejecutada");
         }

    }
    
    public function listarColores(){
        JToolbarHelper::title('Gestión de colores');
        $colores = Color::paginate(20);
        return myView::render("admin.catalogo.lista_colores", ["colores" => $colores]);
    }
    
    public function formColor($color){
        JToolbarHelper::title('Gestión de colores');
        return myView::render("admin.catalogo.form_color", ["color" => $color]);
    }
    
    public function crearColor(){
        return $this->formColor(new Color());
    }
    
    public function editarColor(){
        $idColor = myApp::getRequest()->getVar("id");
        $color = Color::find($idColor);
        
        if (!sizeof($color)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarColores", "Color no encontrado");
        }
        
        return $this->formColor($color);
    }
    
    public function guardarColor(){
        $request = myApp::getRequest();
        $idColor = myApp::getRequest()->getVar("id");
        $color = Color::find($idColor);
        
        if (!sizeof($color)){
            $color = new Color();
        }
        
        $color->fill($request->all());
        
        if ($color->save()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarColores", "Color guardado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarColores", "No se pudo guardar el color");
        }
    }
    
    public function borrarColor(){
        $idColor = myApp::getRequest()->getVar("id");
        $color = Color::find($idColor);
        
        if (!sizeof($color)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarColores", "Color no encontrado");
        }        
        
        if ($color->delete()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarColores", "Color borrado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarColores", "No se pudo borrar el color");
        }
    }
    
    public function listarTallas(){
        JToolbarHelper::title('Gestión de tallas');
        $tallas = Talla::paginate(20);
        return myView::render("admin.catalogo.lista_tallas", ["tallas" => $tallas]);
    }
    
    public function formTalla($talla){
        JToolbarHelper::title('Gestión de tallas');
        return myView::render("admin.catalogo.form_talla", ["talla" => $talla]);
    }
    
    public function crearTalla(){
        return $this->formTalla(new Talla());
    }
    
    public function editarTalla(){
        $idTalla = myApp::getRequest()->getVar("id");
        $talla = Talla::find($idTalla);
        
        if (!sizeof($talla)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarTallas", "Talla no encontrada");    
        }
        
        return $this->formTalla($talla);
    }
    
    public function guardarTalla(){
        $request = myApp::getRequest();
        $idTalla = myApp::getRequest()->getVar("id");
        $talla = Talla::find($idTalla);
        
        if (!sizeof($talla)){
            $talla = new Talla();
        }
        
        $talla->fill($request->all());
        
        if ($talla->save()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarTallas", "Talla guardada");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarTallas", "No se pudo guardar la talla");
        }
    }
    
    public function borrarTalla(){
        $idTalla = myApp::getRequest()->getVar("id");
        $talla = Talla::find($idTalla);
        
        if (!sizeof($talla)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarTalla", "Talla no encontrada");
        }        
        
        if ($talla->delete()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarTalla", "Talla borrada");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarTalla", "No se pudo borrar la talla");
        }
    }
    
    public function listarAtributos(){
        JToolbarHelper::title('Gestión de atributos');
        $atributos = Atributo::paginate(20);
        return myView::render("admin.catalogo.lista_atributos", ["atributos" => $atributos]);
    }
    
    public function formAtributo($atributo){
        JToolbarHelper::title('Gestión de atributos');
        return myView::render("admin.catalogo.form_atributo", ["atributo" => $atributo]);
    }
    
    public function crearAtributo(){
        return $this->formAtributo(new Atributo());
    }
    
    public function editarAtributo(){
        $idAtrib = myApp::getRequest()->getVar("id");
        $atributo = Atributo::find($idAtrib);
        
        if (!sizeof($atributo)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarAtributos", "Atributo no encontrado");    
        }
        
        return $this->formAtributo($atributo);
    }
    
    public function guardarAtributo(){
        $request = myApp::getRequest();
        $idAtrib = myApp::getRequest()->getVar("id");
        $atributo = Atributo::find($idAtrib);
        
        if (!sizeof($atributo)){
            $atributo = new Atributo();
        }
        
        $atributo->fill($request->all());
        
        if ($atributo->save()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarAtributos", "Atributo guardado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarAtributos", "No se pudo guardar el atributo");
        }
    }
    
    public function borrarAtributo(){
        $idAtrib = myApp::getRequest()->getVar("id");
        $atributo = Atributo::find($idAtrib);
        
        if (!sizeof($atributo)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarAtributos", "Atributo no encontrado");
        }        
        
        if ($atributo->delete()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarAtributos", "Atributo borrado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarAtributos", "No se pudo borrar el atributo");
        }
    }
    
    public function listarCategorias(){
        JToolbarHelper::title('Gestión de categorías');
        $categorias = CategoriaRef::paginate(20);
        return myView::render("admin.catalogo.lista_categorias", ["categorias" => $categorias]);
    }
    
    public function formCategoria($categoria){
        JToolbarHelper::title('Gestión de categorias');
        $categorias = CategoriaRef::all();
        return myView::render("admin.catalogo.form_categoria", ["categoria" => $categoria, "categorias" => $categorias]);
    }
    
    public function crearCategoria(){
        return $this->formCategoria(new CategoriaRef());
    }
    
    public function editarCategoria(){
        $idCat = myApp::getRequest()->getVar("id");
        $categoria = CategoriaRef::find($idCat);
        
        if (!sizeof($categoria)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarCategorias", "Categoría no encontrada");    
        }
        
        return $this->formCategoria($categoria);
    }
    
    public function guardarCategoria(){
        $request = myApp::getRequest();
        $idCat = myApp::getRequest()->getVar("id");
        $categoria = CategoriaRef::find($idCat);
        
        if (!sizeof($categoria)){
            $categoria = new CategoriaRef();
        }
        
        $categoria->fill($request->all());
        
        if ($categoria->save()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarCategorias", "Categoria guardada");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarCategorias", "No se pudo guardar la categoria");
        }
    }
    
    public function borrarCategoria(){
        $idCat = myApp::getRequest()->getVar("id");
        $categoria = CategoriaRef::find($idCat);
        
        if (!sizeof($categoria)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarCategorias", "Categoría no encontrada");
        }        
        
        if ($categoria->delete()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarCategorias", "Categoría borrada");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarCategorias", "No se pudo borrar la cartegoría");
        }
    }

    public function listarExtensiones(){
        $idProd = myApp::getRequest()->getVar("id");
        $producto = Producto::where("id", $idProd)->with(["extensiones.talla", "extensiones.color"])->first();
        return myView::render("admin.catalogo.lista_extensiones", ["producto" => $producto]);
    }

    public function editarExtension(){
        $idExt = myApp::getRequest()->getVar("id");
        $idProd = myApp::getRequest()->getVar("id_producto");
        $extension = Extension::find($idExt);

        if (!sizeof($extension)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=editarExtension&id_producto=".$idProd."&format=raw", "Extensión no encontrada");
        }

        $colores = Color::all();
        $tallas = Talla::all();

        return myView::render("admin.catalogo.form_extension", ["extension" => $extension, "colores" => $colores, "tallas" => $tallas]);
    }

    public function guardarExtension(){
        $request = myApp::getRequest();
        $idExt = $request->getVar("id");
        $extension = Extension::find($idExt);
        if (!sizeof($extension)){
            $idProd = $request->getVar("id_referencia");
            $idTalla = $request->getVar("id_talla");
            $idColor = $request->getVar("id_color");
            $extension = Extension::where('id_referencia', $idProd)->where('id_talla', $idTalla)->where('id_color', $idColor)->first();
        }

        if (!sizeof($extension)){
            $extension = new Extension();
        }

        $extension->fill($request->get());
        $extension->save();
        myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarExtensiones&id=".$extension->id_referencia."&format=raw");
    }

    public function borrarExtension(){
        $request = myApp::getRequest();
        $idExt = $request->getVar("id");
        $idProd = $request->getVar("id_referencia");
        $extension = Extension::find($idCat);
        
        if (!sizeof($extension)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarExtensiones&id=".$idProd."&format=raw");
        }        
        
        $categoria->delete();
        myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarExtensiones&id=".$idProd."&format=raw");
    }

    public function listarImagenes($idProd=""){        
        if (empty($idProd)){
            $idProd = myApp::getRequest()->getVar("id");    
        }
        $producto = Producto::where("id", $idProd)->with("imagenes")->first();
        return myView::render("admin.catalogo.lista_imagenes", ["producto" => $producto, "urlImg" => myApp::urlImg()]);
    }

    public function agregarImagenes(){
        //print_r($_FILES);
        $idProd = myApp::getRequest()->getVar("id");
        $producto = Producto::find($idProd);
        $imagenes = [];

        foreach($_FILES as $f){
            if (is_array($f) && is_uploaded_file($f["tmp_name"]) && !$f['error']){
			    $dirs = array(myApp::pathImg(), "productos", $idProd);

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
			    $nombreArchivo = $idProd."_".uniqid().".".$info["extension"];
			    move_uploaded_file($f['tmp_name'], $dir.DS.$nombreArchivo);			    
			    $func = myApp::getFunciones();
			    if (is_file($dir.DS.$nombreArchivo)){
				    $func->crearThumb($nombreArchivo, $dir, $dir.DS."thumb", 150, 150);
			    }

                $img = new ImagenRef();
                $img->id_referencia = $idProd;
                $img->archivo = $nombreArchivo;
                $img->descripcion = $producto->nombre;

                $imagenes[] = $img;
		   }
        }

        $producto->imagenes()->saveMany($imagenes);
        return $this->listarImagenes($idProd);
    }
    
    public function listarProductos(){
        JToolbarHelper::title('Gestión de productos');
        $productos = Producto::paginate(20);
        return myView::render("admin.catalogo.lista_productos", ["productos" => $productos]);
    }
    
    public function formProducto($producto){
        JToolbarHelper::title('Gestión de productos');        
        
        $categorias = CategoriaRef::with(array('productos' => function($query) use ($producto) {
            $query->where('id_referencia', $producto->id);
        }))->get();
        
        $atributos = Atributo::with(array('atributosRef' => function($query) use ($producto) {
            $query->where('id_referencia', $producto->id);
        }))->get();

        $colores = Color::all();
        $tallas = Talla::all();

        return myView::render("admin.catalogo.form_producto", ["producto" => $producto, "categorias" => $categorias, "atributos" => $atributos, "colores" => $colores, "tallas" => $tallas, "urlImg" => myApp::urlImg()]);
    }
    
    public function crearProducto(){
        return $this->formProducto(new Producto());
    }
    
    public function editarProducto(){
        $idProd = myApp::getRequest()->getVar("id");
        $producto = Producto::where("id", $idProd)->with(["extensiones.talla", "extensiones.color", "imagenes"])->first();

        if (!sizeof($producto)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarProductos", "Producto no encontrado");    
        }        

        return $this->formProducto($producto);
    }
    
    public function guardarProducto(){
        $request = myApp::getRequest();
        $idProd = myApp::getRequest()->getVar("id");
        $producto = Producto::find($idProd);
        
        if (!sizeof($producto)){
            $producto = new Producto();
        }
        
        $producto->fill($request->all());
        
        if ($producto->save()){
            $categorias = $request->getVar("categorias", [], "ARRAY");
            $atributos = $request->getVar("atributos", [], "ARRAY");
            $atributosId = $request->getVar("atributos_id", [], "ARRAY");
            $atributosRef = [];
            foreach($atributosId as $key => $idAtributoRef){
                $atributoRef = AtributoRef::find($idAtributoRef);
                if (!sizeof($atributoRef)){
                    $atributoRef = new AtributoRef();
                    $atributoRef->id_referencia = $producto->id;
                    $atributoRef->id_atributo = $key;
                }

                $atributoRef->valor = $atributos[$key];
                $atributosRef[] = $atributoRef;
            }            

            $producto->atributosRef()->saveMany($atributosRef);
            $producto->categorias()->detach();
            $producto->categorias()->attach($categorias);

            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarProductos", "Producto guardado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarProductos", "No se pudo guardar el producto");
        }
    }
    
    public function borrarProducto(){
        $idProd = myApp::getRequest()->getVar("id");
        $producto = Producto::find($idProd);
        
        if (!sizeof($producto)){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarProductos", "Producto no encontrado");
        }        
        
        if ($producto->delete()){
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarProductos", "Producto borrado");
        }
        else{
            myApp::redirect("index.php?option=com_my_component&controller=adminCatalogo&task=listarProductos", "No se pudo borrar el producto");
        }
    }
}
