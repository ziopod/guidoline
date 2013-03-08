<?php

class View_User_Index extends View_Layout {
	
	/**
	* Custom values
	*/
	public $title = "Guidoline — Users";
	public $users;

	/**
	* Get all users
	**/
	public function users()
	{

		//return $this->users->as_array(); /* Une fois la DB en place */
		return $this->users;

	}

}