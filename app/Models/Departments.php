<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use ValidatorFactory;

class Departments extends Model {

	// use SoftDeletes;

	protected $table;
	protected $guarded = ['id'];
    public    $hidden = ['deleted_at', 'updated_at', 'created_at'];
    protected $fillable = ['department'];
    public    $timestamps = true;
    protected $errors;
    protected $rules = [
		"department" =>  "required|alpha_spaces"
    ];

    public function errors()
    {
    	return $this->errors;
    }
}