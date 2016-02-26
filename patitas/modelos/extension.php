<?php

class Extension extends myEloquent {    
    protected $table = 'my_cat_extension';
    protected $fillable = array('id_referencia', 'id_color', 'id_talla', 'mod_valor');
    
    public function color(){
        return $this->belongsTo('Color', 'id_color');
    }
    
    public function talla(){
        return $this->belongsTo('Talla', 'id_talla');
    }
    
    public function producto(){
        return $this->belongsTo('Producto', 'id_referencia');
    }
}
