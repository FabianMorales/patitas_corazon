<?php

class CategoriaDoc extends myEloquent {    
    protected $table = 'my_gdoc_categoria';
    protected $fillable = array('nombre');
    
    function documentos(){
        return $this->belongsToMany('Documento', 'my_gdoc_catdoc', 'id_categoria', 'id_documento');
    }
}
