<?php
class usuarioController extends myController{
    function index(){
        $paises = Pais::all();
        $jusuario = JFactory::getUser();
        $musuario = myUsuario::find($jsuario->id);        
        $estados = sizeof($musuario) ? Departamento::where("id_pais", $musuario->id_pais)->get() : [];
        return myView::render("usuario.form_usuario", ["jusuario" => $jusuario, "paises" => $paises, "estados" => $estados]);
    }
    
    function mostrarLogin($redirect = ""){
        if (empty($redirect)){
            $redirect = myApp::getRequest()->getVar("redirect", base64_encode("index.php?option=com_my_component&controller=documento"), "base64");    
        }
        else{
            $redirect = base64_encode($redirect);
        }
        
        $usuario = JFactory::getUser();
        if (!$usuario->id){
            return myView::render("usuario.form_login", ["redirect" => $redirect]);
        }
        else{
            return myView::render("usuario.logout", ["user" => $usuario]);
        }
    }
    
    function mostrarWidget(){
        $usuario = JFactory::getUser();
        return myView::render("usuario.widget", ["user" => $usuario]);
    }

    function login($username="", $password="", $redirect="", $noredir = false){  
        $req = myApp::getRequest();
        if (!$redirect){
            if ($redirect = $req->getVar("redirect", "", "base64")) {
                $redirect = base64_decode($redirect);
                if (!JURI::isInternal($redirect)) {
                    $redirect = '';
                }
            }
        }

        if (!$username){
            $username = $req->getVar('username_login', '', 'username');
        }

        if (!$password){
            $password = $req->getVar('password_login', '', "RAW");
        }        

        $opciones = array();
        $opciones['remember'] = false;
        $opciones['return'] = $redirect;

        $credenciales = array();
        $credenciales['username'] = $username;
        $credenciales['password'] = $password;		
        $error = myApp::login($credenciales, $opciones);
				
        if(!JError::isError($error)){
            if (!$noredir){
                if (!$redirect) {
                    $redirect = 'index.php?option=com_my_component&controller=usuario';
                }
                
                myApp::redirect($redirect);                
            }
        }
        else{        	
            myApp::mostrarMensaje($error->message, "error");
        }
    }

    function logout($noredir = false){
        $error = myApp::logout();
        $req = myApp::getRequest();
        if (!$noredir){
            if(!JError::isError($error)){
                if ($redirect = $req->getVar('redirect', '', 'base64')){
                    $redirect = base64_decode($redirect);
                    if (!JURI::isInternal($redirect)) {
                        $redirect = 'index.php';
                    }
                }
                else{
                    $redirect = 'index.php';
                }

                if ($redirect && !(strpos($redirect, 'com_my_component'))){
                    myApp::redirect($redirect);
                }
            }
            else{
                myApp::redirect('index.php?option=com_my_component&controller=usuario');
            }            
        }
    }

    function guardarUsuario(){
        jimport('joomla.user.helper');
        $modelo = myApp::getModelo();
        $request = myApp::getRequest();
        
        $nuevo = false;
        $fecha = date('Y-m-d H:i:s');
        
        $usuario = new Usuario();
        $myUsuario = new MyUsuario();
        $usuarioJoomla = JFactory::getUser();
        if ($usuarioJoomla->id){
            $usuario = Usuario::find($usuarioJoomla->id);
            $myUsuario_aux = MyUsuario::where("id", $usuarioJoomla->id)->first();
            if (sizeof($myUsuario_aux)){
                $myUsuario = $myUsuario_aux;
            }
        }
        else{
            $usuario->name = $request->getVar("nombre");            
            $usuario->activation = substr(uniqid(), 1, 100);
            $usuario->registerDate = $fecha;
            $usuario->params = '';
            $nuevo = true;
        }
        
        $passwordOrig = $usuario->password;
        
        $usuario->username = $request->getVar("email");
        $usuario->lastvisitDate = $fecha;
        $password = $request->getVar('password', '', JREQUEST_ALLOWRAW);
        $usuario->fill($request->all());       
        $myUsuario->fill($request->all());
        
        $grupo = $modelo->getGrupoUser();

        if ($redirect = $request->getVar('_redirect', '', 'base64')) {
            $redirect = base64_decode($redirect);
            if (!JURI::isInternal($redirect)) {
                $redirect = '';
            }
        }
        
        if (!$password && !$usuarioJoomla->id){
            myApp::mostrarMensaje("Ingrese la clave", "error");
            return false;
        }
		
        $usuarioEmail = Usuario::where("email", $usuario->email)->first();        
        if (sizeof($usuarioEmail) && $usuarioEmail->id != $usuarioJoomla->id){
            myApp::mostrarMensaje("Esta direcci&oacute;n de correo ya est&aacute; en uso", "error");
            return;
        }
        
        $usuarioCed = MyUsuario::where("id_cc", $myUsuario->id_cc)->first();        
        if (sizeof($usuarioCed) && $usuarioCed->id != $usuarioJoomla->id){
            myApp::mostrarMensaje("Este n&uacute;mero de c&eacute;dula ya est&aacute; en uso", "error");
            return;
        }

        if ($password){
            $salt = JUserHelper::genRandomPassword(32);
            $crypt = JUserHelper::getCryptedPassword($password, $salt);
            $usuario->password = $crypt.':'.$salt;
        }
        else{
            $usuario->password = $passwordOrig;
        }
        
        $exito = false;                
        if ($usuario->save()){
            $myUsuario->id = $usuario->id;
            
            if ($myUsuario->save()){
            	if ($nuevo){
                    if ($modelo->guardarUsuarioGrupo($usuario->id, $grupo["id"])){
                        $exito = true;
                        $urlImagenes = JUri::root()."images/";
                        $mensaje = myView::render("usuario.correo_cuenta_nueva", ["urlImagenes" => $urlImagenes, "urlSitio" => JUri::root(), "usuario" => $usuario, "compania" => $myUsuario]);

                        $jcfg = new JConfig();
                        $mail =& JFactory::getMailer();
                        $mail->addRecipient($usuario->email);
                        $mail->setSender(array($jcfg->mailfrom, $jcfg->fromname));
                        $mail->setSubject("New account");
                        $mail->IsHTML(1);	
                        $mail->setBody($mensaje);
                        $mail->Send();
                    }
                }
                else{
                    $exito = true;
                }
            }
        }

        if ($exito){
            if ($nuevo){
                myApp::mostrarMensaje("Se ha creado exitosamente su cuenta de usuario","message");	
            }
            else{
                myApp::mostrarMensaje("Se ha creado exitosamente su cuenta de usuario. En breve, recibir&aacute; un mensaje de correo para validar su cuenta.","message");
            }			
        }		
        else{
            myApp::mostrarMensaje("No se pudo crear la cuenta","error");	
        }

        return myView::render("usuario.blanco");
    }
	
    function activarUsuario(){
        $idUsuario = myApp::getRequest()->getVar("idUsuario");
        $token = myApp::getRequest()->getVar("token");

        $user = Usuario::find($idUsuario);
        if (!$user->id){
            myApp::mostrarMensaje("La cuenta no es valida", "error");
            return false;
        }		

        if ($user->activation == 0){
            myApp::mostrarMensaje("Esta cuenta ya se encuentra activada", "error");
            return false;
        }

        if ($user->activation != $token){
            myApp::mostrarMensaje("El c&oacute;digo de validaci&oacute;n no es correcto", "error");
            return false;
        }

        $user->activation = 0;
        
        if ($user->save()){
            myApp::mostrarMensaje("Su cuenta ha sido activada exitosamente", "message");
        }
        else{
            myApp::mostrarMensaje("No se pudo activar su cuenta", "error");
        }

        return myView::render("usuario.blanco");
    }
}
?>