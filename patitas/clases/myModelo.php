<?php
/*
	Héctor Fabián Morales Ramírez
	Tecnólogo en Ingeniería de Sistemas
	Enero 2011
*/

use \Illuminate\Database\Capsule\Manager as Capsule;  

class myModelo{   
    public static function boot(){
        myApp::getEloquent();
        
        $modelos = glob(dirname(__DIR__).DS."modelos".DS."*.php");

        foreach ($modelos as $m){
            require_once $m;            
        }
    }
    
    function myModelo(){
        
    }    
}