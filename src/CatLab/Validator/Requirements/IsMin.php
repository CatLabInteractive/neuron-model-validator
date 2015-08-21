<?php
namespace CatLab\Validator\Requirements;

use CatLab\Validator\Models\Property;

class IsMin
	extends Requirement {

	private $value;

	public function __construct ($value)
	{
		$this->value = $value;
	}

	public function validate ($value, Property $property)
	{
		if (!isset ($value))
			return true;

		return $value >= $this->value;
	}

}