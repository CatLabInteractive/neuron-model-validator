<?php

use CatLab\Validator\Validator;

require '../vendor/autoload.php';

$validator = Validator::fromSwagger ('specs/');

echo '<pre>';
$result = $validator->validate ('PackBaseOutput', array (

	'pack' => array (
		'id' => 1,
		'name' => 'My pack',
		'type' => 'round',
		'owner' => array (
			'id' => 1,
			'name' => 'Thijs',
			'avatar' => 'url.com',
			'age' => 28
		)
	)

));

if ($result)
{
	echo '<p><strong>Success!</strong></p>';
}
else {
	echo '<p><strong>Failure</strong></p>';
	foreach ($validator->getErrors () as $v) {
		echo '<p>' . $v . '</p>';
	}
}