<?php

class Pedido extends myEloquent {    
    protected $table = 'my_pedido';
    protected $fillable = array('nombre', 'edad_usuario', 'email', 'celular', 'telefono', 'id_ciudad', 'direccion', 'id_forma_pago', 'regalo', 'observaciones');
    
    public function ciudad(){
        return $this->hasOne('Ciudad', 'id', 'id_ciudad');
    }
    
    public function formaPago(){
        return $this->hasOne('FormaPago', 'id', 'id_forma_pago');
    }
    
    public function detalle(){
        return $this->hasMany("DetallePedido", "id_pedido");
    }
}
