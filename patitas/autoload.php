<?php

if (!defined("DS")){
    define("DS", DIRECTORY_SEPARATOR);
    define('BASE_DIR', dirname(__DIR__));    
    
    $coreDir = __DIR__."/";
    $clasesDir = $coreDir."clases/";
    $libDir = $coreDir."lib/";
    $archivos = [
        $clasesDir."myApp.php",
        $clasesDir."myConfig.php",
        $clasesDir."myDocumento.php",
        $clasesDir."myFunciones.php",
        $clasesDir."myModelo.php",
        $clasesDir."myController.php",
        $clasesDir."myAdminController.php",
        $clasesDir."myView.php",
        $clasesDir."myRequest.php",
        $clasesDir."myRoute.php",
        $clasesDir."mySession.php",
        $libDir.'laravel/autoload.php',
        $clasesDir.'myEloquent.php',
        $libDir."Twig/Autoloader.php",
        $libDir."Slim/Slim.php",
        $libDir."PHPMailer/PHPMailerAutoload.php"
    ];

    foreach ($archivos as $a){
        require_once $a;
    }
    
    mySession::boot();
    myModelo::boot();
    myView::boot();
    $app = myRoute::boot();
}