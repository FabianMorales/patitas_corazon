<?php

class Widget extends myEloquent {    
    protected $table = 'my_wid_widget';
    protected $fillable = array('nombre', 'clase_css', 'ancho', 'alto');
    
    public function puntosMapa(){
        return $this->hasMany('PuntoMapa', 'id_widget');
    }
    
    public function itemsSlider(){
        return $this->hasMany('ItemSlider', 'id_widget');
    }
}
