<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use ValidatorFactory;

class Todos extends Model {

	use SoftDeletes;

	protected $table;
	protected $guarded = ['id'];
    public    $timestamps = true;
    protected $errors;
    protected $rules = [
		"userid" =>  "required|integer",
		"title" => "required|alpha",
    ];

    public function errors()
    {
    	return $this->errors;
    }

}