<?php

class Producto extends myEloquent {    
    protected $table = 'my_cat_referencia';
    protected $fillable = array('nombre', 'referencia', 'descripcion', 'valor_base', 'existencias', 'tipo');
    
    function categorias(){
        return $this->belongsToMany('CategoriaRef', 'my_cat_catref', 'id_referencia', 'id_categoria');
    }
    
    function extensiones(){
        return $this->hasMany('Extension', 'id_referencia');
    }
    
    function atributosRef(){
        return $this->hasMany('AtributoRef', 'id_referencia');
    }

    function imagenes(){
        return $this->hasMany('ImagenRef', 'id_referencia');
    }
}
