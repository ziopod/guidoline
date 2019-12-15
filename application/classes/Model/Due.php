<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Dues model
 *
 * @package    Guidoline
 * @category   Model
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2013 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

class Model_Due extends ORM {

	/**
	 * @var Array  Belongs to relationships
	 */
	protected $_belongs_to = array(
		'member' => array(),
		'form' => array(),
	);

	/**
	 * @var Array All actives dues
	 */
	protected $_actives;

	/**
	 * @var Array All currencies storage
	 */
	protected $_currencies;

  public function save(Validation $validation = NULL)
  {

    // $this->amount = $this->amount ? $this->amount : $this->form->price;
    // echo Debug::vars($this->form->price); exit;
    parent::save($validation);

  }
	/**
	 * Additionnal related embeddable data
	 *
	 * @return Array
	 */
	public function embeddable()
	{
		return array(
			'pretty_created' => 'pretty_created',
			'member' => 'member',
			'form' => 'form',
			'all_forms' => 'all_forms',
		);
	}

	/**
	 * Get actives dues
	 *
   * @return Array
	 */
	public function actives()
	{
		if ( ! $this->_actives)
		{
			$this->_actives = array(
				'records' => array(),
				'records_count' => NULL,
			);

			$this->where('date_end', '>', DB::expr('NOW()'));

			foreach ($this->find_all() as $active)
			{
				$this->_actives['records'][]['due'] = $active;
			}

			$this->_actives['records_count'] = count($this->_actives['records']);
		}

		return $this->_actives;
	}

	/**
	 * Pretty period start
	 *
	 * @return String
	 */
	public function pretty_date_start()
	{
		return strftime(__('date.short'), strtotime($this->date_start));
	}

  /**
   * Pretty created date
   */
  public function pretty_created()
  {
    return strftime(__('date.medium'), strtotime($this->created));
  }

	/**
	 * Pretty period end
	 *
	 * @return String
	 */
	public function pretty_date_end()
	{
		return strftime(__('date.short'), strtotime($this->date_end));
	}

  /**
   * Due URL
   *
   * @return String
   */
  public function url()
  {
    return URL::site(Route::get('due.detail')->uri(array('due_id' => $this->pk())), TRUE);
  }

	/**
	 * Active or not
	 *
	 * @return Boolean
	 */
	public function is_active()
	{
		return $this->date_end >= date('Y-m-d');
	}

	/**
	 * Trigger some methods when export as array
	 *
	 * @param  String  $embed_paths    Related data to embed
	 * @return Array
	 */
	public function as_array($embed_paths = NULL)
	{
		$object = parent::as_array($embed_paths);
		// Periods stuffs
		$object['is_active'] = $this->is_active();
		$object['url'] = $this->url();
		$object['pretty_created'] = $this->pretty_created();
		$object['pretty_date_start'] = $this->pretty_date_start();
		$object['pretty_date_end'] = $this->pretty_date_end();
    $embed = $this->_embed($embed_paths);
		return array_merge($object, $embed);
  }

}
