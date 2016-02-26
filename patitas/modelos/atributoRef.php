<?php

class AtributoRef extends myEloquent {    
    protected $table = 'my_cat_atributoref';
    
    public function producto(){
        return $this->belongsTo('Producto', 'id_referencia');
    }

    public function atributo(){
        return $this->belongsTo('Atributo', 'id_atributo');
    }
}
