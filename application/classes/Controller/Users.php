<?php defined('SYSPATH') or die ('No direct script access');

class Controller_Users extends Controller_Website {

	public function action_index()
	{
		$view = new View_User_Index;
		$view->title = "Guidoline - List all users";
		// $users = DB::select('username', 'email')->from('users')->execute();
		// $result = (object) NULL;
		// $result->users = $users->as_array();
		// $result->users_count = count($users);
		// return $result;
		// return ORM::factory('user')->find_all();
		/* La base de données n'est pas encore en place, nous utiliserons le tableau suivant à la place */
		$DB =  array(
			array(
				'id'		=> 1,
				'firstname'	=> 'Bertrand',
				'name'		=> 'Keller',
				'address'	=> 'rue du Sacre',
				'zipcode'	=> '76000',
				'city'		=> 'ROUEN',
				'job'		=> 'Compositeur',
				'email'		=> 'bertrand@example.com',
				),
			array(
				'id'		=> 2,
				'firstname'	=> 'Steve',
				'name'		=> 'Beau',
				'address'	=> 'place de la rougemare',
				'zipcode'	=> '76000',
				'city'		=> 'ROUEN',
				'job'		=> 'Orthographeur',
				'email'		=> 'steve@example.com',
				),
			array(
				'id'		=> 3,
				'firstname'	=> 'Alexandre',
				'name'		=> 'Ronsault',
				'address'	=> '1, place du docteur Alfred Cerné',
				'zipcode'	=> '76000',
				'city'		=> 'ROUEN',
				'job'		=> 'Contemporain',
				'email'		=> 'alexandre@example.com',
				),

			);
		$data = array();
		$data['users'] = array();
		/* à utiliser si besoin de filter les données en plus de as_array('var', 'var', 'var') */
		// foreach ($data as $user)
		// {
		// 	$users['users'][] = $user;
		// }
		$data['users'] = $DB;
		$data['count'] = count($DB);

		$view->users = $data;

		if ($this->request->param('format') == 'json')
		{
			// Json
			$this->request->headers('Content-Type', 'application/json; charset='.Kohana::$charset);
			/* Les headers HTTP ne sont pas corrects, à corriger */
			//$this->request->body(json_encode($users));
			//$this->response->send_headers();
			$this->response->body(Json_encode($data));
			return;
		}

		$this->response->body($this->layout->render($view));
	}
}