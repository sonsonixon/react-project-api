<?php 
namespace App\Controllers;

use App\Models\Users;
use ValidatorFactory;
  
class UsersController{

	// protected $dates = ['deleted_at'];

	protected $validator;

	public function join($request, $response, $args) {
		$users = new Users;
		$all = $users->join('departments', 'users.department', '=', 'departments.id')
					 ->select([
					 	'departments.department AS department', 
					 	'users.first_name',
					 	'users.last_name',
					 ])
					 ->get();

		return $response->withJSON($all);
	}

	public function login($request, $response, $args) {

		$params = $request->getParsedBody();
		$user = $this->authenticate($params);

		if (is_numeric($user)) {
			switch($user) {
				case 400:
					return $response->withJSON(["code" => "error", "title" => "Login Error", "message" => "Password not match"]);
					break;
				case 404:
					return $response->withJSON(["code" => "error", "title" => "Login Error", "message" => "Credentials not found"]);
					break;
				default:
					// do nothing
			}
		}

		$token = $user->generateToken($user->uuid);
		return $response->withJSON(["code" => "success", "token" => $token, "uuid" => $user->uuid]);
	}

	public function authenticate($params) {

		$username = $params['data']['username'];
		$password = $params['data']['password'];

		$users = new Users;
		$user = $users->where('username', $username)->first();

		if(!$user) {
			return 404;
		}

		$match = $user->checkPassword($password);

		if($match) {
			return $user;
		} else {
			return 400;
		}
	}

	public function fetchUsers($request, $response, $args) {	

		$params = $request->getParsedBody();
		$offset = ($params['page']) * $params['pageSize'];
		$users = new Users();

		$params['pageSize'] = $params['pageSize'] ?? 10;
		$total = $users->count();
		$pages = ceil($total / $params['pageSize']);
		
		$users = $users->skip($offset)
					   ->take($params['pageSize'])
					   ->orderBy('created_at', 'DESC')
					   ->get();
					   
		return $response->withJSON([
			'rows'  => $users,
			'pages' => $pages
		]);
	}

	public function createUser($request, $response, $args) {

		$params = $request->getParsedBody();
		$users = new Users;
		
		if($users->validate($params['data'])){
			$users->save();
		}
		else{
			return $response->withJSON([
				"code" => "ERROR",
				"errors" => $users->errors()
			]);
		}

		return $response->withJSON([
			"code" => "SUCCESS",
			"module" => "USERS",
			"title" => "Creation Success",
			"message" => "User has been successfully created",			
		]);
	}

	public function getCurrentUser($request, $response, $args) {
		$params = $request->getParsedBody();

		$users = new Users;
		$users = $users->where('uuid', $args['uuid'])->first();

		return $response->withJSON($users);
	}

	public function fetchUser($request, $response, $args) {

		$params = $request->getParsedBody();

		$users = new Users;
		$users = $this->validateUuid($args);

		if(is_numeric($users)) {
			return $response->withJSON([
				"code" => "NOTFOUND",
				"title" => "404 Not Found",
				"message" => "Data not found",
			]);
		}

		return $response->withJSON([
			"code" => "SUCCESS",
			"module" => "USERS",
			"data" => $users,
		]);
	}

	public function updateUser($request, $response, $args) {

		$params = $request->getParsedBody();

		$rules = [
			"first_name"  => "required|alpha_spaces|max:100",
	        "middle_name" => "required|alpha_spaces|max:100",
			"last_name"   => "required|alpha_spaces|max:100",
			"username"    => "required|alpha_num|min:6|max:50",
	        "status"      => "required",
	        "role"        => "required",
		];

		$validator = new ValidatorFactory();
		$v = $validator->make($params['data'], $rules);

		if ($v->passes()) {
			$users = new Users;
			$users->where('uuid', $args['uuid'])->update($params['data']);
		}
		else{
			return $response->withJSON([
				"code" => "ERROR",
				"errors" => $v->errors()
			]);
		}

		return $response->withJSON([
			"code" => "SUCCESS",
			"title" => "Success",
			"message" => "User has been successfully updated",
		]);
	}

	public function changePassword($request, $response, $args) {

		$params = $request->getParsedBody();
		$password = $params['data']['password'];

		$users = new Users;

		$users = $this->validateUuid($args);

		if(is_numeric($users)) {
			return $response->withJSON([
				"code" => "NOTFOUND",
				"title" => "404 Not Found",
				"message" => "Data not found",
			]);
		}

		$rules = [
			"password"  => "required|min:8|max:250|confirmed",
		];

		$validator = new ValidatorFactory();
		$v = $validator->make($params['data'], $rules);

		if($v->passes()){
			$users = $users->find($args['uuid']);	
			$users->password = password_hash($password, PASSWORD_DEFAULT);
			$users->save();
		} else {
			return $response->withJSON([
				"code" => "ERROR",
				"errors" => $v->errors(),
			]);
		}

		return $response->withJSON([
			"code" => "SUCCESS",
			"title" => "Success",
			"message" => "Password has been successfully update",
		]);
	}

	public function validateUuid($args) {

		$users = new Users;
		$users = $users->where('uuid', $args['uuid'])->first();

		if(!$users) {
			return 404;
		}

		return $users;
	}

	/*
	public function delete($request,$response, $args)
	{
		$flight = Table::find($args['id']);

		$flight->delete();			

		return $response->withJSON(["message" => "Successfully Deleted"]);
	}
	*/

}