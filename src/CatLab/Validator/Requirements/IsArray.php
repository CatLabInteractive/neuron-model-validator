<?php
namespace CatLab\Validator\Requirements;

use CatLab\Validator\Models\Model;
use CatLab\Validator\Models\Property;

class IsArray
	extends IsType {

	public function __construct ()
	{
		parent::__construct('array');
	}
}