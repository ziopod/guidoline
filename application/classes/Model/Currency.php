<?php defined('SYSPATH') OR die('No direct script access');

/**
 * Currency model
 *
 * @package    Guidoline
 * @category   Model
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2013 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

class Model_Currency extends ORM {

	/**
	 * @var String Primary key
	 */
	protected $_primary_key = 'code';

	/**
	 * @var Array All curencies
	 */
	protected $_currencies;

	/**
	 * Get all currencies from DB
	 *
	 * @param  String  $current_currency_code  Currency marked as current
	 * @return Array
	 */
	public function find_all_as_array($current_currency_code = NULL)
	{

		if ( ! $this->_currencies)
		{
			$this->_currencies = array(
				'records' => array(),
				'records_count' => NULL,
			);

			foreach (ORM::factory('Currency')->find_all() as $currency)
			{
				$currency = $currency->as_array();
				$currency['current'] = $currency['code'] === $current_currency_code;
        // Required for HTML form
        $currency['label'] = $currency['name'];
        $currency['value'] = $currency['code'];

        $this->_currencies['records'][]['currency'] = $currency;
			}

      $this->_currencies['records_count'] = count($this->_currencies['records']);
		}

		return $this->_currencies;
	}

}
