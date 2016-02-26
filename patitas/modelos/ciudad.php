<?php

class Ciudad extends myEloquent {    
    protected $table = 'my_sis_ciudad';
    
    public function depto(){
        return $this->belongsTo('Departamento', 'id_depto')->first();
    }    
}
