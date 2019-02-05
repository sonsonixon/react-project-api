<?php 
	namespace App\Controllers;


	use App\Models\Table;

	
	class TableController{

		// protected $dates = ['deleted_at'];


		public function __construct()
		{
			
		}


    	public function get($request,$response, $args)
		{
			$table = new Table();
			return $response->withJSON($table->all());
		}


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

	}