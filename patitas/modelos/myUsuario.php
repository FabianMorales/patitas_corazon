<?php

class MyUsuario extends myEloquent {    
    protected $table = 'my_sis_user';
    protected $fillable = array('id', 'id_cc', 'nombre', 'apellido', 'direccion', 'telefono', 'celular', 'id_pais', 'depto', 'ciudad');
    
    public function usuario(){
        return $this->belongsTo("Usuario", "id", "id");
    }
}
