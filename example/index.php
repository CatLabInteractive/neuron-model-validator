<?php

use CatLab\Validator\Validator;

require '../vendor/autoload.php';

//$validator = \CatLab\Validator\Validator::fromSwagger ('http://api.quizwitz.com/specs/');

$validator = new Validator ();

$tests = 0;

function testData ($specs, $data)
{
	global $tests;
	global $validator;

	$tests ++;

	$modelName = 'model' . $tests;

	echo '<h1>Test ' . $tests . '</h1>';

	$validator->addModel (\CatLab\Validator\Models\Model::make ($modelName, $specs));

	if ($validator->validate ($modelName, $data))
	{
		echo '<p><strong>Success!</strong></p>';
	}
	else {
		echo '<p><strong>Failed.</strong></p>';
		foreach ($validator->getErrors () as $error)
		{
			echo '<p>' . $error . '</p>';
		}
	}
}

testData (
	array (
		'id' => 'required|numeric',
		'name' => 'string'
	),
	array (
		'id' => 1
	)
);

testData (
	array (
		'id' => 'required|numeric',
		'name' => 'required|string'
	),
	array (
		'id' => 1
	)
);

testData (
	array (
		'id' => 'required|numeric',
		'name' => 'string'
	),
	array (
		'id' => 1,
		'name' => 'Thijs'
	)
);

testData (
	array (
		'message' => 'required|string',
		'user?' => array (
			'id' => 'numeric|required',
			'name' => 'string'
		)
	),
	array (
		'message' => 'This is a message'
	)
);

testData (
	array (
		'message' => 'required|string',
		'user' => array (
			'id' => 'numeric|required',
			'name' => 'string'
		)
	),
	array (
		'message' => 'This is a message'
	)
);

testData (
	array (
		'message' => 'required|string',
		'user' => array (
			'id' => 'numeric|required',
			'name' => 'string'
		)
	),
	array (
		'message' => 'This is a message',
		'user' => array (
			'bla' => 'yep'
		)
	)
);


testData (
	array (
		'message' => 'required|string',
		'user' => array (
			'id' => 'numeric|required',
			'company' => array (
				'id' => 'numeric|required',
				'name' => 'string'
			)
		)
	),
	array (
		'message' => 'This is a message',
		'user' => array (
			'id' => 1
		)
	)
);

testData (
	array (
		'message' => 'required|string',
		'user' => array (
			'id' => 'numeric|required',
			'company' => array (
				'id' => 'numeric|required',
				'name' => 'string'
			)
		)
	),
	array (
		'message' => 'This is a message',
		'user' => array (
			'id' => 1,
			'company' => array (

			)
		)
	)
);