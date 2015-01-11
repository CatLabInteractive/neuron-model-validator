<?php
namespace CatLab\Validator\Requirements;

use CatLab\Validator\Models\Property;

abstract class Requirement {

	public function __construct ()
	{

	}

	public function validate ($value)
	{
		return true;
	}

	public function getError (Property $property)
	{
		return 'Property ' . $property->getName () . ' does not match requirement ' . get_class ($this);
	}

}