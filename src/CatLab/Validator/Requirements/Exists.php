<?php
namespace CatLab\Validator\Requirements;

use CatLab\Validator\Models\Property;

class Exists
	extends Requirement {

	public function __construct ()
	{

	}

	public function validate ($value, Property $property)
	{
		return isset ($value);
	}

}