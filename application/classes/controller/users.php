<?php defined('SYSPATH') or die ('No direct script access');

class Controller_Users extends Controller_Website {

	public function action_index()
	{
		$this->response->body($this->layout->render(new View_Users));
	}
}