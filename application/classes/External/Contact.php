<?php

/**
 * Driver interface for connection and action for external api service
 *
 * @package    Guidoline
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2021 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

 class External_Contact {
  /**
   * @var Object API driver
   */
  protected $_driver;

  /**
   * Load driver
   *
   * @return External
   */
  public function __construct()
  {
    $driver = 'External_Driver_Contact_' . Kohana::$config->load('guidoline')->api_drivers['contact'];
    $this->_driver = new $driver;
  }

  /**
   * USer Account information
   */
  public function informations()
  {
    return $this->_driver->informations();
  }

  /**
   * Get contact
   */
  public function get($email)
  {
    return $this->_driver->get($email);
  }

  /**
   * Create or update contact
   */
  public function save($email, $data)
  {

    if (!$email) return;

    $exist = $this->get($email);

    if (isset($exist))
    {
      return $this->_driver->update($email, $data);
    }

    return $this->_driver->create($email, $data);

  }
}
