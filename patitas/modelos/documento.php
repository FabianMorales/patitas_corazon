<?php

class Documento extends myEloquent {    
    protected $table = 'my_gdoc_documento';
    protected $fillable = array('nombre', 'visibilidad', 'descripcion', 'publicado');
    
    function categorias(){
        return $this->belongsToMany('CategoriaDoc', 'my_gdoc_catdoc', 'id_documento', 'id_categoria');
    }
}
