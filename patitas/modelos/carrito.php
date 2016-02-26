<?php

use \Illuminate\Database\Capsule\Manager as Capsule;

class Carrito extends myEloquent {    
    protected $table = 'my_cart_carrito';
    
    public function producto(){
        return $this->belongsTo('Producto', 'id_referencia');
    }

    public function extension(){
        return $this->belongsTo('Extension', 'id_ext');
    }

    public static function totales($idSesion){
        return Capsule::table('my_carrito')
               ->leftJoin('my_cat_referencia', 'my_cat_referencia.id', '=', 'my_cart_carrito.id_referencia')
               ->where('id_sesion', $idSesion)        
               ->select(Capsule::raw('sum(cantidad) as cantidad_total'), Capsule::raw('sum(cantidad * valor_base) as pesos_total'))
               ->first();
    }
}
