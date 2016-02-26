<?php
/*
	Héctor Fabián Morales Ramírez
	Tecnólogo en Ingeniería de Sistemas
	Enero 2011
*/
use \Illuminate\Database\Capsule\Manager as Capsule;

class myApp{
    static $document;
    static $modelo;
    static $func;	
    static $request;
    static $eloquent;

    public static function getController($nombre = ""){
        if (empty($nombre)){
            $req = myApp::getRequest();
            $nombre = $req->getVar("controller");
        }
        
        $rutaController = dirname(__DIR__)."/controladores/".$nombre."Controller.php";
        
        $claseController = $nombre."Controller";
        if (is_file($rutaController)){
            require_once($rutaController);
        }
        
        return new $claseController();
    }
    
    public static function redirect($url, $mensaje=""){
        //$app = JFactory::getApplication();
        //$app->redirect($url, $mensaje);
        header('Location: '.$url);
    }
    
    public static function getUrlRoot(){
        $cfg = new myConfig();
        return (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $cfg->urlSitio;
    }

    /*public static function login($credenciales, $opciones){
        $app = JFactory::getApplication();
        return $app->login($credenciales, $opciones);
    }

    public static function logout(){
        $app = JFactory::getApplication();
        return $app->logout();
    }*/

    public static function getModelo(){
        if (!myApp::$modelo){
            myApp::$modelo = new myModelo();
        }
        return myApp::$modelo;
    }
    
    public static function getEloquent(){
        if (!myApp::$eloquent){
            $cfg = new myConfig();
            
            myApp::$eloquent = new Capsule;
            myApp::$eloquent->addConnection(array(
                'driver'    => $cfg->driver,
                'host'      => $cfg->host,
                'database'  => $cfg->database,
                'username'  => $cfg->username,
                'password'  => $cfg->password,
                'charset'   => $cfg->charset,
                'collation' => $cfg->collation,
                'prefix'    => $cfg->prefix
            ));
            
            myApp::$eloquent->setAsGlobal();
            myApp::$eloquent->bootEloquent();
        }
        
        return myApp::$eloquent;
    }
    
    public static function getDocumento(){
        if (!myApp::$document){
            myApp::$document = new myDocumento();
        }
        return myApp::$document;
    }

    public static function getFunciones(){
        if (!myApp::$func){
            myApp::$func = new myFunciones();
        }
        return myApp::$func;
    }

    public static function getRequest(){
        if (!myApp::$request){
            myApp::$request = new myRequest();
        }
        return myApp::$request;
    }

    public static function getLang(){        
        $lang = mySession::get("myLang", "es");
        return $lang;
    }

    public static function setLang($lang){        
        $lang = mySession::set("myLang", $lang);
    }

    public static function pathImg(){
        return BASE_DIR.DS."myImagenes";
    }

    public static function urlImg(){
        return myApp::getUrlRoot()."myImagenes/";
    }
    
    public static function pathDocumentos(){
        $dir = dirname(BASE_DIR).DS."myArchivos";
        if(!is_dir($dir)){ 
            @mkdir($dir);
        }
        return $dir.DS."documentos";
    }
    
    /*public static function enviarEmail(){
        $mail = new PHPMailer;

        //$mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'desarrollo@encubo.ws';
        $mail->Password = 'marisol2009';
        $mail->SMTPSecure = 'tls';

        $mail->From = 'info@editorialscar.com';
        $mail->FromName = 'Contacto Editorial Escar';
        $mail->addAddress('desarrollo@encubo.ws', 'Contacto Editorial Escar');
        //$mail->addReplyTo('info@example.com', 'Information');
        $mail->addBCC('desarrollo@encubo.ws', 'Desarrollo');

        $mail->WordWrap = 50;
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
        $mail->isHTML(true);

        $mail->Subject = $contacto["asunto"];
        $mail->Body    = myView::render("contacto.correo", array("contacto" => $contacto));
        
        if(!$mail->send()) {
            return 'No se pudo enviar el mensaje. Intente nuevamente.';
        } 
        else {
            return 'Su correo ha sido enviado satisfactoriamente, en breve nos pondremos en contacto';
        }
    }

    /*public static function mostrarMensaje($mensaje, $tipo=""){
        JFactory::getApplication()->enqueueMessage(JText::_($mensaje), $tipo);
        return $mensaje;
    }*/
}