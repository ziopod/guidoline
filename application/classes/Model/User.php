<?php defined('SYSPATH') OR die('No direct script access');

/**
 * User Model
 *
 * @package    Guidoline
 * @category   Model
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2013 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

class Model_User extends Model_Auth_User {

	/**
	 * @var Array  Has one relationship
	 */
	protected $_has_one = array(
		'member' => array(),
	);

	/**
	 * @var Array Has many relationship
	 */
	protected $_has_many = array(
		'roles' => array(
			'through' => 'roles_users',
		)
	);

	/**
	 * @var Array Created column
	 */
	protected $_created_column = array(
		'column' => 'created',
		'format' => 'Y-m-d h:i:s',
	);

	/**
	 * @var Array Updated column
	 */
	protected $_updated_column = array(
		'column' => 'updated',
		'format' => 'Y-m-d h:i:s',
	);

	/**
	 * @var Array Higher user role strorage
	 */
	protected $_role;

	/**
	 * @var Array Current user roles storage
	 */
	protected $_roles;

	/**
	 * @var Array All roles with user role marked as current
	 */
	protected $_all_roles;

	/**
	 * Extend parent::create()
	 *
	 * Generate random password and random unique username
	 *
	 * @param  Validation   $validation  Validation object
	 * @throws Kohana_Exception
	 * @return ORM
	 */
	public function create(Validation $validation = NULL)
	{
		if ( ! $this->_loaded)
		{
			// create unique username
			$this->username = Text::random();

			while (ORM::factory('User')->where('username', '=', $this->username)->loaded())
			{
				$this->username = Text::random();
			}

			$this->password = sha1(uniqid(NULL, TRUE));
		}

		parent::create($validation);
	}

	/**
	 * Additionnal related embeddable data
	 *
	 * ~~~
	 * array(
	 *	index_name => relationship_name
	 *	index_name => array(
	 *		'value_index_ame' => 'specific_value_name'
	 *	)
	 * ~~
	 *
	 * @return Array
	 */
	public function embeddable()
	{
		return array(
			'role' => 'role',
			'role_all' => 'role_all',
		);
	}

	/**
	 * Extend ORM as_array for include processing on data
	 *
	 * @param  String   $embed_paths Related data to embed
	 * @return Array
	 */
	public function as_array($embed_paths = NULL)
	{
		// Raw from database
		$object = parent::as_array();
		$object['loaded'] = $this->loaded();
		// Automatically embbeded roles
		$object['roles'] = $this->roles();
    // Embeded values
    $embed = $this->_embed($embed_paths);
		return array_merge($object, $embed);
	}

	/**
	 * Weighter current user role
	 *
	 * @return Array
	 */
	 public function role()
	{

		if ( ! $this->_role)
		{
			$this->_role = $this->roles->order_by('id', 'desc')->find()->as_array();
		}

		return $this->_role;
	}

	/**
	 * All user roles sorted by weight
	 *
	 * @return Array  Array of ORM objects
	 */
	public function roles()
	{
		if ( ! $this->_roles)
		{
			$this->_roles = array(
				'records' => array(),
				'records_count' => NULL,
			);

			foreach ($this->roles->order_by('id', 'desc')->find_all() as $role)
			{
				$this->_roles['records'][]['role'] = $role->as_array();
			}

			$this->_roles['records_count'] = count($this->_roles['records']);
		}

		return $this->_roles;
	}

	/**
	 * Find all roles and set user roles as current
	 *
	 * @return Array
	 */
	public function all_roles()
	{
		if ( ! $this->_all_roles)
		{
			$this->_all_roles = array(
				'records'       => array(),
				'records_count' => NULL,
			);

			$current_roles = $this->roles->find_all()->as_array('id', 'name', 'description');

			foreach (ORM::factory('role')->find_all() as $role)
			{
				$role = $role->as_array();
				$role['current'] = isset($current_roles[$role['id']]);
				$this->_all_roles['records'][]['role'] = $role;
			}

			$this->_all_roles['records_count'] = count($this->_all_roles['records']);
		}

		return $this->_all_roles;
	}
}
