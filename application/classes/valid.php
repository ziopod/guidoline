<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Validation rules.
 *
 * @package    Kohana
 * @category   Security
 * @author     Kohana Team
 * @copyright  (c) 2008-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Valid extends Kohana_Valid {

	/**
	 * Checks if a model object already exists for a field with the given values.
	 *
	 * @param   array    Validation object
	 * @param   string   model name
	 * @param   string   primary field name
	 * @param   array    array with other field names
	 * @return  boolean
	 */

	// $other_fields = context
	// Par ex. : La validation du nom ($field) d'un album ($model) doit être unique pour une artist ($other_array)
	public static function already_exists($array, $model, $field, $other_fields = array())
	{
		$obj = ORM::Factory($model)->where($field,'=',$array[$field]);
		foreach ($other_fields as $fld)
		{
			$obj = $obj->and_where($fld,'=',$array[$fld]);
		}
		if (isset($array['id']))
		{
			$obj = $obj->and_where('id','!=',$array['id']);
		}
		return ($obj->count_all() == 0);
	}

}
