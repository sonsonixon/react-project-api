<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use ValidatorFactory;



class Model extends \Illuminate\Database\Eloquent\Model {

    protected $errors;
    protected $rules = [];


    public static function getResolver()
    {
        return parent::$resolver;
    }

  	public function validate($data)
    {
        $factory = new ValidatorFactory();
        $v = $factory->make($data, $this->rules);

        if($v->passes()){
        	 foreach ($data as $key => $value) {
        	 	$this->{$key} = $value;
        	 }
        }else{
        	$this->errors = $v->errors();
        }


        return $v->passes();
    }

    public function errors()
    {
    	return $this->errors;
    }

}