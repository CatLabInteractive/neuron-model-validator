<?php
namespace CatLab\Validator\Models\Requirements;

class Exists
	extends Requirement {

	public function __construct ()
	{

	}

	public function validate ($value)
	{
		return isset ($value);
	}

}