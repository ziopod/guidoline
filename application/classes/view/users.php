<?php

class View_Users extends View_Layout {
	
	/**
	* Custom values
	*/
	public $title = "Guidoline — Users";

	/**
	* Get all users
	**/
	public function users()
	{
		// $users = DB::select('username', 'email')->from('users')->execute();
		// $result = (object) NULL;
		// $result->users = $users->as_array();
		// $result->users_count = count($users);
		// return $result;
//		return ORM::factory('user')->find_all();
	}

	function count()
	{
		return ORM::factory('user')->count_all();
	}

}