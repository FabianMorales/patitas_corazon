<?php

class myRoute {
    public static function boot(){
        Slim\Slim::registerAutoloader();
        $app = new Slim\Slim();
        myRequest::boot($app->request->params());
        $app->get('/', myController::_("home@index"));
        $app->get('/linea/educativa', myController::_("home@educacion"));
        $app->get('/linea/salud', myController::_("home@salud"));
        $app->get('/linea/variedad', myController::_("home@variedad"));
        $app->get('/linea/cocina', myController::_("home@cocina"));
        $app->get('/linea/literatura', myController::_("home@literatura"));
        $app->post('/contacto/solicitud', myController::_("contacto@enviarCorreoContacto"));
        $app->post('/contacto/suscripcion', myController::_("contacto@enviarCorreoSuscripcion"));
        $app->get('/hello/:name/:apellido', myController::_("home@saludar"));		
        $app->get('/app/ver/:cod', myController::_("home@verificarCodigo"));		
        $app->run();
        return $app;
    }
}
