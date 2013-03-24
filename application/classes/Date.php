<?php defined('SYSPATH') or die ('No direct script access');

class Date extends Kohana_Date {

	/**
	* Return formatted date span has string
	*
	* @param	integer	$remote timestamp to find the span of
	* @param	integer	$local  timestamp to use as the baseline
	* @param	string 	$output formatting string
	* @return	string 	when only a single output is requested
	* @return	string	formatted date span
	**/
	public static function formatted_span($remote, $local = NULL, $output = 'years,months,weeks,days,hours,minutes,seconds')
	{
		$formatted_span = Date::span($remote, $local, $output);			
		$output = '';

		foreach ($formatted_span as $key => $value)
		{
			if ($value)
			{
				$output .= $value . ' ' . $key .', ';
			}
		}

		return $output;
	}
}