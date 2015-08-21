<?php
namespace CatLab\Validator\Requirements;

use CatLab\Validator\Models\Property;

class NotEmpty
	extends Requirement {

	public function __construct ()
	{

	}

	/**
	 * @param $value
	 * @param Property $property
	 * @return bool
	 */
	public function validate ($value, Property $property)
	{
		if (!isset ($value))
			return true;

		return !empty ($value);
	}

}