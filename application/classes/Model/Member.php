<?php defined('SYSPATH') or die ('No direct script access');

/**
 * Le modèle ORM pour la table "users"
 *
* @package    Guidoline
* @category   Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */
 
class Model_Member extends ORM{

	/**
	* Relationship
	**/
	protected $_belongs_to = array(
		'user'	=> array(
			'model'	=> 'User',
		),
		'status'	=> array(
			'model'	=> 'Status',
		),
	);



	/**
	* Règles de validation
	**/
	public function rules()
	{
		return array(
			'email' => array(
				array('not_empty'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 128)),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			//	array('already_exists', array(':validation', 'user', ':field'))
			),
		);
	}

	/**
	* Labels
	**/
	public function labels()
	{
		return array(
			'email'		=> 'Email',
		);
	}

	/**
	* Filtres pour les données de formulaires
	**/
	public function filters()
	{
		return array(
			'email' => array(
				array('trim', array(':value')),
			),
		);
	}

	/**
	* Test si une valeur de clef est unique dans la base de données
	*
	* @param	mixte	valeur à tester
	* @param	string	field name
	* @return	boolean
	*/
	public function unique_key_exists($value, $field = NULL)
	{
		if ($field === NULL)
		{
			$field = $this->unique_key($value);
		}

		return (bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
			->from($this->_table_name)
			->where($field, '=', $value)
			->where($this->_primary_key, '!=', $this->pk())
			->execute($this->_db)
			->get_('total_count');
	}

	/**
	* Retourne la liste des statut disponibles et "marque" le status courant
	*
	* @return Object
	**/

	public function statuses()
	{
		$statuses = DB::select('id', 'name')->from('statuses')->execute()->as_array();
		$current_status_key = array_search(
			array(
				'id'=>$this->status->id,
				'name' => $this->status->name
			), $statuses);
		$statuses[$current_status_key]['selected'] = TRUE;
		return $statuses;
	}
}