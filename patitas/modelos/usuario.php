<?php

class Usuario extends myEloquent {    
    protected $table = 'users';
    protected $fillable = array('id', 'name', 'username', 'email', 'password');
    
    public function my(){
        return $this->hasOne("MyUsuario", "id");
    }
}
