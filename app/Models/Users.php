<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use ValidatorFactory;
use App\Traits\Uuids;

use \Firebase\JWT\JWT;

/**** DEPRECATED DEPENDECY ****/
// use Namshi\JOSE\SimpleJWS;

class Users extends Model {

	use SoftDeletes;
    use Uuids;

    protected $primaryKey = 'uid';
	protected $table = "users";
    protected $keyType = 'string';
    protected $guarded = ['uid'];
    protected $fillable = ['uid'];
    public    $hidden  = ['uid', 'password', 'deleted_at', 'updated_at'];
    public    $timestamps = true;
    public    $incrementing = false;
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

    public function generateToken($uid) {

        /*** Firebase/PHP-JWT ***/

        // $publicKey = openssl_pkey_get_public("file://".__DIR__."/../../keys/id_rsa_jwt.pub");
        // $decoded = JWT::decode($token, $publicKey, array('RS256'));
        // $decoded_array = (array) $decoded;

        /*** List of possible token payload claims
         * "typ" => type -> header
         * "alg" => algorithm -> header
         * "iss" => issuer -> payload
         * "aud" => audience -> payload
         * "iat" => issue at -> payload
         * "exp" => expiration time -> payload
         * "auth_time" => authentication time -> payload
        ***/

        /*** Note: 
         * You can add public claims like user ID, name or email in the payload claims.
         * You can also add private claim names. These are subject to collision, so use them with caution.
        ***/

        $current_time = time();

        $payload = array(
            "typ" => "JWT",
            "alg" => "RS256",
            "iss" => "React Project",
            "aud" => "http://localhost:3000",
            "iat" => $current_time,
            "exp" => $current_time+(60*60), // expires in 1 hour
            "uid" => $uid,
        );

        $privateKey = openssl_pkey_get_private("file://".__DIR__."/../../keys/id_rsa_jwt.pem");

        $token = JWT::encode($payload, $privateKey, 'RS256');

        return $token;

        /*** DEPRECATED DEPENDECY ***/
        /*** Namshi/JOSE/SimpleJWS ***/
        /**
         * $jws  = new SimpleJWS(array(
         *      'alg' => 'RS256'
         * ));
         * $jws->setPayload(array(
         *      'user' => $this->toJson(),
         * ));
         *
         * $privateKey = \openssl_pkey_get_private("file://".__DIR__."/../../keys/privateKey.private", 'sonsonixon');
         *
         * $jws->sign($privateKey);
         *
         * return $jws->getTokenString(); 
        **/
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