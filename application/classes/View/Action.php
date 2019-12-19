<?php

class View_Action {

  public $auto_render;

  public function __construct()
  {
    $action = Request::current()->param('do');
    $this->$action();
  }

  public function member_set_volunteer()
  {
    $member = ORM::factory('Member', request::current()->param('id'));

    if ( ! $member->loaded())
    {
      throw HTTP_Exception_404();
    }

    $member->is_volunteer = $member->is_volunteer == 0 ? 1 : 0;
    $member->save();
  }
}
