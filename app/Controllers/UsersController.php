<?php 
namespace App\Controllers;

use App\Models\Users;

class UsersController{

	// protected $dates = ['deleted_at'];

	public function __construct()
	{
		
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

		$token = $user->generateToken();
		return $response->withJSON(["code" => "success", "token" => $token, "data" => $user]);
	}

	public function authenticate($params) {

		$username = $params['data']['username'];
		$password = $params['data']['password'];

		$user = new Users;
		$user = $user->where('username', $username)->first();

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
			'rows' => $users,
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
			return $response->withJSON(["code" => "error", "module" => "users", "errors" => $table->errors()]);
		}

		return $response->withJSON(["code" => "success", "title" => "Success", "message" => "User has been successfully created"]);
	}


	/*
	public function add($request,$response, $args)
	{
		$params = $request->getParsedBody();

		$table = new Table;
		
		if($table->validate($params)){
			$table->save();
		}else{
			return $response->withJSON($table->errors());
		}

	

		return $response->withJSON(["message" => "Successfully Created"]);
	}


	public function update($request,$response, $args)
	{

		$params = $request->getParsedBody();

		Table::where('id',$args['id'])->update($params);


		return $response->withJSON(["message" => "Successfully Updated"]);
	}


	public function delete($request,$response, $args)
	{
		$flight = Table::find($args['id']);

		$flight->delete();			

		return $response->withJSON(["message" => "Successfully Deleted"]);
	}
	*/

}