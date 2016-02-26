<?php

class CategoriaRef extends myEloquent {    
    protected $table = 'my_cat_categoria';
    protected $fillable = array('nombre', 'id_cat');
    
    function productos(){
        return $this->belongsToMany('Producto', 'my_cat_catref', 'id_categoria', 'id_referencia');
    }
}
