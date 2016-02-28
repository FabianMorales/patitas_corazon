<?php

class myRoute {

    public static function boot() {
        Slim\Slim::registerAutoloader();
        $app = new Slim\Slim();
        myRequest::boot($app->request->params());
        $app->get('/', myController::_("home@index"));
        $app->get('/mision', myController::_("home@mision"));
        $app->get('/vision', myController::_("home@vision"));
        $app->get('/nosotros', myController::_("home@nosotros"));
        $app->get('/adopta/gato', myController::_("home@adoptaGato"));
        $app->get('/adopta/perro', myController::_("home@adoptaPerro"));
        $app->get('/contacto', myController::_("home@contacto"));

        $app->run();
        return $app;
    }

}
