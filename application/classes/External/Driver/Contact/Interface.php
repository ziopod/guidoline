<?php

/**
 * Driver interface for external contact service
 *
 * @package    Guidoline
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2021 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

interface External_Driver_Contact_Interface
{
  /**
   * User Account informations
   *
   * @return Array Array of informations
   */
  public function informations();

  /**
   * Get contact
   *
   * @param String User email
   */
  public function get($email);

  /**
   * Create contact
   *
   * @param String User email
   * @param Array Array of data
   */
  public function create($email, $data);

  /**
   * Update contact
   *
   * @param String User email
   * @param Array Array of data
   */
  public function update($email, $data);

}
