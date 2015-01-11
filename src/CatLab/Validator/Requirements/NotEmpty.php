<?php
namespace CatLab\Validator\Requirements;

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