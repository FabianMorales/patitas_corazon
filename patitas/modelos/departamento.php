<?php

class Departamento extends myEloquent {    
    protected $table = 'my_sis_depto';
       
    public function pais(){
        return $this->belongsTo('Pais', 'id_pais');
    } 
}
