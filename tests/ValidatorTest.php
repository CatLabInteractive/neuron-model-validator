<?php

namespace CatLab\Validator\Tests;

use CatLab\Validator\Models\Model;
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

		$this->assertEquals (true, $validator->validate ('testModel', $input));
	}

}