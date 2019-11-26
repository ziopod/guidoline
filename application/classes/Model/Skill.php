<?php defined('SYSPATH') OR die('No direct script access');

/**
* Skills model
*
* @package    Guidoline
* @category   Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

class Model_Skill extends ORM {

	/**
	* @var array Has many relationship
	**/
	protected $_has_many = array(
		'members' => array(
			'through' => 'members_skills',
		),
	);

	/**
	 * Conctructor
	 *
	 * Do garbage collector
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);
		$this->garbage_collector();
	}

	/**
	* Skill url
	*
	* @return string
	**/
	public function url()
	{
		return URL::site(Route::get('skill.show')->uri(array('skill_id' => $this->pk())), TRUE);
	}

	/**
	* Delete unuse skills
	*
	* @return void
	**/
	public function garbage_collector()
	{
		if (mt_rand(1, 500) === 1)
		{
			// Select useless skills
			$query = DB::select('id')
				->from($this->_table_name)
				->join($this->_has_many['memberships']['through'], 'LEFT')
				->on($this->_primary_key, '=', $this->_has_many['memberships']['foreign_key'])
				->where('membership_id', '=', NULL)
				->execute()->as_array();

			if ($query)
			{
				// Remove useless skills
				DB::delete($this->_table_name)
					->where('id', 'IN', array_values($query))
					->execute();
			}

			return $this;
		}
	}

	/**
	* Extend ORM as_array for include processing on data
	*
	* @param  string  Related data to embed (ex. user.email,statuses)
	* @return array
	**/
	public function as_array($embed_path = NULL)
	{
		// Raw from database
		$object = parent::as_array();
		// URL
		$object['url'] = $this->url();

    $embed = $this->_embed($embed_paths);
		return array_merge($object, $embed);
	}
}
