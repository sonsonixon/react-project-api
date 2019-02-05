<?php 
namespace App\Controllers;

use App\Models\Todos;

class TodosController{

	// protected $dates = ['deleted_at'];

	public function __construct()
	{
		
	}

	public function fetch($request, $response, $args)
	{	
		$params = $request->getParsedBody();

		$offset = ($params['page']) * $params['pageSize'];

		$todos = new Todos();

		$params['pageSize'] = $params['pageSize'] ?? 10;
		$total = $todos->count();
		$pages = ceil($total / $params['pageSize']);
		$todos = $todos->skip($offset)
					   ->take($params['pageSize'])
					   ->orderBy('created_at', 'DESC')
					   ->get();
		
		return $response->withJSON([
			'rows' => $todos,
			'pages' => $pages
		]);
	}

	public function add($request, $response, $args)
	{
		$params = $request->getParsedBody();

		$table = new Todos;
		
		if($table->validate($params['data'])){
			$table->save();
		}
		else{
			return $response->withJSON(["code" => "error", "module" => "todos", "errors" => $table->errors()]);
		}

		return $response->withJSON(["code" => "success", "title" => "Success", "message" => "Task has been added to list"]);
	}


	/*

	$params = $request->getQueryParams();
	$page = $params['page'];
	$size = $params['size'];

	$list = new JobOffer();
	$list = $list->join('clients', 'job_offers.client_uuid', '=', 'clients.client_uuid');



	$count = $list->count();
	$output = [];

	// var_dump($count, ($count / $size) * $page);exit;
	$list = $list->skip($size * $page);
	$list = $list->take($size);

	$output['data'] = $list->get();
	$output['page'] = $page;
	$output['size'] = $size;

	return $response->withJSON($output);

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