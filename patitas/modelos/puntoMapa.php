<?php

class PuntoMapa extends myEloquent {    
    protected $table = 'my_wid_mapapunto';
    protected $fillable = array('id_widget', 'descripcion', 'latitud', 'longitud', 'info', 'centro');
}
