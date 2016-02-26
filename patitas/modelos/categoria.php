<?php

class Categoria extends myEloquent {    
    protected $table = 'categories';
    
    public function articulos(){
        return $this->hasMany('Articulo', 'catid');
    }
}
