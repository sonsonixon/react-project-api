<?php 
namespace App\Controllers;

use App\Models\Departments;

class DepartmentsController {

	// protected $dates = ['deleted_at'];

	public function getall($request, $response, $args) {
		$params = $request->getParsedBody();
		$offset = ($params['page']) * $params['pageSize'];
		$departments = new Departments();

		$params['pageSize'] = $params['pageSize'] ?? 10;
		$total = $departments->count();
		$pages = ceil($total / $params['pageSize']);

		$departments = $departments->skip($offset)
				   	   ->take($params['pageSize'])
				   	   ->orderBy('created_at', 'DESC')
				   	   ->get();

		return $response->withJSON([
			'rows'  => $departments,
			'pages' => $pages,
		]);
	}

	public function getoption($request, $response, $args) {
		$departments = new Departments;
		return $response->withJSON([
			"code" => "departments",
			"data" => $departments->orderBy('department', 'ASC')->get(),
		]);
	}

	public function get() {

	}

	public function add($request, $response, $args) {
		$params = $request->getParsedBody();

		$departments = new Departments;

		if($departments->validate($params['data'])) {
			$departments->save();
		} else {
			return $response->withJSON([
				"code" => "ERROR",
				"errors" => $departments->errors(),
			]);
		}

		return $response->withJSON([
			"code" => "SUCCESS", 
			"title" => "Success",
			"message" => "Department has been successfully added",
		]);
	}

	public function update() {

	}

	public function delete() {

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