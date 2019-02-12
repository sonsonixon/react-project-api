<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use ValidatorFactory;

class Users extends Model {

	use SoftDeletes;

	protected $table;
	protected $guarded = ['user_id'];
    public    $hidden = ['password', 'deleted_at'];
    public    $timestamps = true;
    protected $errors;
    protected $rules = [
		"first_name"  => "required|alpha_spaces|max:100",
        "middle_name" => "required|alpha_spaces|max:100",
		"last_name"   => "required|alpha_spaces|max:100",
		"username"    => "required|alpha_num|min:6|max:50|unique:users",
        "password"    => "required|min:8|max:250",
        "status"      => "required",
        "role"        => "required",
    ];

    public function checkPassword($password) {
        return password_verify($password, $this->password);
    }

    public function generateToken() {

        $jws  = new SimpleJWS(array(
            'alg' => 'RS256'
        ));
        $jws->setPayload(array(
            'user' => $this->toJson(),
        ));

        $privateKey = \openssl_pkey_get_private("file://".__DIR__."/../../key/mykey.private", 'john');
 
        $jws->sign($privateKey);
        
        return $jws->getTokenString();
    }

    public function validate($data, $rules = null) {
        $factory = new ValidatorFactory();

        if($rules === null){
            $rules = $this->rules;
        }

        $v = $factory->make($data, $rules);

        if ($v->passes()) {
            foreach ($data as $key => $value) {
                if ($key === "password") {
                    // hash password
                    $this->{$key} = password_hash($value, PASSWORD_DEFAULT);
                } else {
                    if(isset($rules[$key])){
                        $this->{$key} = $value;
                    }
                }
            }
        } else {
            $this->errors = $v->errors();
        }
        return $v->passes();
    }

    public function errors()
    {
    	return $this->errors;
    }

}