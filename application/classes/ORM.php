<?php defined('SYSPATH') OR die('No direct script access.');

class ORM extends Kohana_ORM {


	/**
	 * Filter nullish
   *
   * @return Mixed
	 */
	static public function nullish($value)
	{
    if (
      $value === '0000-00-00 00:00:00'
      || $value === '0000-00-00'
      || $value == NULL
      )
    {
      return NULL;
    }

		return $value;
  }

  /**
   * Filter convert to case title
   */
  static public function case_title($value)
  {
    $value = trim($value);
    $words = explode("-", $value);
    $result = array();

    foreach ($words as $word)
    {
      $result[] = mb_convert_case($word, MB_CASE_TITLE, "UTF-8");
    }

    $result = implode('-', $result);
    return $result;
  }
   /**
	* Additionnal related embeddable data
	*
	* Embed your path in array like that :
	*
	* ~~~
	* 'user' => array(
	*	'email' => 'your_email_property',
	* 'statuses' => 'your_statuses_method',
	* )
	* ~~~
	*
	* @return array
	**/
	public function embeddable()
	{
		return array();
	}

	/**
	* Attempt retreiving values from method or property targeted by $embed_paths
	*
  * Method and property are autodetected
  *
	* ORM::with() for one-to-one
	* ORM::select($columns) for limited ouput values
	* JOIN for belongs-to or many-to-many
	*
	* @param  string  Embed path (eg. `user.email,statuses`)
	* @return array
	**/
	protected function _embed($embed_paths)
	{
		$embeds = explode(',', $embed_paths);
		$result = array();
		$embedded = $this->embeddable();

		foreach ($embeds as $embed)
		{
			$keys = explode('.', $embed);
			$array = & $result;
			$value_path = $this;

			while (count($keys) > 1)
			{
				$key = array_shift($keys);

				if ( ! isset($array[$key]))
				{
					$array[$key] = array();
				}

				// Nothing found, abort
				if( ! isset($value_path->$key))
				{
					// $array = NULL;
					continue 2;
				}

				$array = & $array[$key];
				$value_path = $value_path->$key;
			}

			// Retrieve property or method name
			$key = Arr::path($this->embeddable(), $embed);
			$authorized_keys = null;

			// Refer to relationship
			if (is_array($key))
			{
				$authorized_keys = array_flip($key);
				$key = $embed;
			}

			$value = NULL;

			// Value come from method or property
			if (method_exists($value_path, $key))
			{
				$value = $value_path->$key();
			}
			elseif (isset($value_path->$key))
			{
				$value = $value_path->$key;

				// Value refer to relationship
				if ($value instanceof ORM)
				{
					// dont save this value
					// if ( ! $value_path->$key->loaded())
					// {
					// 	continue;
					// }

					// White list
					$value = $authorized_keys ?
					array_intersect_key(
						$value_path->$key->as_array(),
						$authorized_keys
					) :
					$value_path->$key->as_array();

				}
			}

			if ($value !== NULL)
			{
				$array[$key] = $value;
			}
		}

		return $result;
	}

  /**
   * Records to options
   *
   * HTML Form helper to transform records array to select HTML form
   * options
   *
   * @return Array
   */
  public static function records_to_options($records)
  {

    $options = array();

    foreach ($records['records'] as $record)
    {
      $record = array_values($record)[0];
      $options[]['option'] = array(
        'label' => $record['label'],
        'value' => $record['value'],
        'selected' => $record['current'] ? 'selected' : FALSE,
      );
    }

    return $options;
  }

	/**
	* Extend ORM as_array for include processing on data
	*
	* @param  string  Related data to embed (ex. user.email,statuses)
	* @return array
	**/
	public function as_array($embed_paths = NULL)
	{
		// Raw from database
		$object = parent::as_array();
		// Load state
		$object['loaded'] = $this->loaded();

		// Embedded values
		// echo debug::vars($embed_paths);
		$embed = $this->_embed($embed_paths);
		// echo debug::vars($embed);
		return array_merge($object, $embed);
	}
}
