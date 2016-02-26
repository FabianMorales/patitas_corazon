<?php

class ImagenRef extends myEloquent {    
    protected $table = 'my_cat_imgref';
    
    public function producto(){
        return $this->belongsTo('Producto', 'id', 'id_referencia');
    }
}
