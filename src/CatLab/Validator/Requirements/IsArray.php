<?php
namespace CatLab\Validator\Requirements;

use CatLab\Validator\Models\Model;
use CatLab\Validator\Models\Property;

class IsArray
	extends Requirement {

	public function __construct ()
	{

	}

	public function validate ($value)
	{
		if (!isset ($value))
			return true;

		return is_array ($value);
	}
}