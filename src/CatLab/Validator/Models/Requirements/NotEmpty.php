<?php
namespace CatLab\Validator\Models\Requirements;

class NotEmpty
	extends Requirement {

	public function __construct ()
	{

	}

	public function validate ($value)
	{
		return !empty ($value);
	}

}