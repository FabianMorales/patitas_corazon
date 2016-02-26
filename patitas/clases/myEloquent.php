<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class myEloquent extends Eloquent {
    
    use Sg\Paginator\PaginatorTrait;
    
    public function __construct() {
        $a = array();
        if (sizeof($this->fillable)){
            $d = array_diff($this->fillable, array("id"));
            $v = array_fill(0, sizeof($d), "");
            $a = array_combine($d, $v);            
        }
        
        return parent::__construct($a);
    }
    
    public static function getTableName() { return with(new static)->getTable(); } 
}
