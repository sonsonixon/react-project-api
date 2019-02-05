<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use ValidatorFactory;



class Table extends Model {

	use SoftDeletes;

	protected $table;
	protected $guarded = ['id'];
    public    $timestamps = false;
    protected $errors;
    protected $rules = [
		"name" =>  "required|min:6|unique:tables,name"

    ];

    public function errors()
    {
    	return $this->errors;
    }

}