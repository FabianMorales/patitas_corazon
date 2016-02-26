<?php

class DetallePedido extends myEloquent {    
    protected $table = 'my_detalle_pedido';
    
    public function producto(){
        return $this->belongsTo('Producto', 'id_referencia');
    }

    public function extension(){
        return $this->belongsTo('Extension', 'id_ext');
    }
}
