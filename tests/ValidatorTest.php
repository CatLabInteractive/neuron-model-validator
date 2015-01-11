<?php

namespace CatLab\Validator\Tests;

use CatLab\Validator\Models\Model;
use CatLab\Validator\Models\ModelProperty;
use CatLab\Validator\Models\Property;
use CatLab\Validator\Requirements\Exists;
use CatLab\Validator\Requirements\IsType;
use CatLab\Validator\Requirements\NotEmpty;
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

	public function testDimensions ()
	{
		$input = array (
			'name' => 'This is my name',
			'user' => array (
				'id' => 1,
				'name' => 'Thijs'
			)
		);

		$validator = new Validator ();

		$model = new Model ('testModel');
		$validator->addModel ($model);

		$property = new Property ('name');
		$property->addRequirement (new IsType ('string'));
		$model->addProperty ($property);

		// Submodel
		$userModel = new Model ('user');

		$property = new Property ('id');
		$property->addRequirement (new IsType ('numeric'));
		$userModel->addProperty ($property);

		$property = new Property ('name');
		$property->addRequirement (new IsType ('string'));
		$userModel->addProperty ($property);

		$property = new ModelProperty ($userModel);
		$property->addRequirement (new Exists ());
		$model->addProperty ($property);

		$this->assertEquals (true, $validator->validate ('testModel', $input));
	}

	public function testEmpty ()
	{
		$specs = array ();
		$data = array ();

		$validator = new Validator ();
		$validator->addModel (Model::make ('test', $specs));

		$this->assertTrue ($validator->validate ('test', $data));
	}

	public function testChildrenCorrect ()
	{
		$specs = array (
			'message' => 'required|string',
			'user' => array (
				'id' => 'numeric|required',
				'company' => array (
					'id' => 'numeric|required',
					'name' => 'string|required'
				)
			)
		);

		$data = array (
			'message' => 'This is a message',
			'user' => array (
				'id' => 1,
				'company' => array (
					'id' => 1,
					'name' => 'This is a company name'
				)
			)
		);

		$validator = new Validator ();
		$validator->addModel (Model::make ('test', $specs));

		$this->assertTrue ($validator->validate ('test', $data));
	}

	public function testChildrenIncorrect1 ()
	{
		$specs = array (
			'message' => 'required|string',
			'user' => array (
				'id' => 'numeric|required',
				'company' => array (
					'id' => 'numeric|required',
					'name' => 'string|required'
				)
			)
		);

		$data = array (
			'message' => 'This is a message',
			'user' => array (
				'company' => array (
					'name' => 'This is a company name'
				)
			)
		);

		$validator = new Validator ();
		$validator->addModel (Model::make ('test', $specs));

		$this->assertFalse ($validator->validate ('test', $data));
	}

	public function testChildrenIncorrect2 ()
	{
		$specs = array (
			'message' => 'required|string',
			'user' => array (
				'id' => 'numeric|required',
				'company' => array (
					'id' => 'numeric|required',
					'name' => 'string|required'
				)
			)
		);

		$data = array (
			'message' => 'This is a message',
			'user' => array (
				'id' => 1
			)
		);

		$validator = new Validator ();
		$validator->addModel (Model::make ('test', $specs));

		$this->assertFalse ($validator->validate ('test', $data));
	}

	public function testOptional1 ()
	{
		$specs = array (
			'message' => 'required|string',
			'user' => array (
				'id' => 'numeric|required',
				'company?' => array (
					'id' => 'numeric|required',
					'name' => 'string|required'
				)
			)
		);

		$data = array (
			'message' => 'This is a message',
			'user' => array (
				'id' => 1
			)
		);

		$validator = new Validator ();
		$validator->addModel (Model::make ('test', $specs));

		$this->assertTrue ($validator->validate ('test', $data));
	}

}