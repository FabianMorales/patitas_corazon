<?php

class Atributo extends myEloquent {    
    protected $table = 'my_cat_atributo';
    protected $fillable = array('descripcion', 'portada');
    
    function atributosRef(){
        return $this->hasMany('AtributoRef', 'id_atributo');
    }
}
