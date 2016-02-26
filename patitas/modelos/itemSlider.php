<?php

class ItemSlider extends myEloquent {    
    protected $table = 'my_wid_slideritem';
    protected $fillable = array('id_widget', 'url', 'titulo');
}
