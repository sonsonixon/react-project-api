<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use ValidatorFactory;

class Users extends Model {

	use SoftDeletes;

	protected $table;
	protected $guarded = ['user_id'];
    public    $timestamps = true;
    protected $errors;
    protected $rules = [
		"first_name"  => "required|alpha_spaces",
        "middle_name" => "required|alpha_spaces",
		"last_name"   => "required|alpha_spaces",
		"username"    => "required|alpha_num|min:6",
        "password"    => "required|min:8",
        "status"      => "required",
        "role"        => "required",
    ];

    public function errors()
    {
    	return $this->errors;
    }

}