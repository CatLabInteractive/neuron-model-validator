<?php

namespace CatLab\Validator\Tests;

use CatLab\Validator\Models\Model;
use CatLab\Validator\Models\Property;
use CatLab\Validator\Models\Requirements\Exists;
use CatLab\Validator\Models\Requirements\IsType;
use CatLab\Validator\Models\Requirements\NotEmpty;
use CatLab\Validator\Validator;

class ValidatorTest
	extends \PHPUnit_Framework_TestCase {

	public function testValidator ()
	{
		$input = array (
			'name' => 'This is my name',
			'counter' => 15
		);

		$validator = new Validator ();

		$model = new Model ('testModel');
		$validator->addModel ($model);

		$property = new Property ("name");
		$property->addRequirement (new Exists ());
		$property->addRequirement (new NotEmpty ());
		$property->addRequirement (new IsType ('string'));
		$model->addProperty ($property);

		$property = new Property ("counter");
		$property->addRequirement (new IsType ('numeric'));
		$model->addProperty ($property);

		$this->assertEquals (true, $validator->validate ('testModel', $input));
	}

}