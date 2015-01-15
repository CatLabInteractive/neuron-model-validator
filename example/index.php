<?php

use CatLab\Validator\Validator;

require '../vendor/autoload.php';

$validator = Validator::fromSwagger ('specs/');

$failing = json_decode ('{"id":3,"languages":[{"id":3,"language":"en"}],"category":{"id":1,"name":"qsdf"},"publisher":{"id":7,"name":"daedeloths quizzes","avatar":"https:\/\/www.gravatar.com\/avatar\/372b92179ff1e7351a11db02b2deaaba?d=retro&s=200","age":28,"statistics":{"joined_at":"2015-01-15T16:03:33+01:00","last_online":"2015-01-15T16:03:33+01:00","played":{"questions":100,"packs":5,"quizzes":1},"created":{"questions":100,"packs":5,"quizzes":1}}},"statistics":{"difficulty":null,"rating":null,"rounds":2,"questions":7},"resources":[{"language":"en","name":"A random round","image":"https:\/\/www.gravatar.com\/avatar\/qsdf?d=retro&s=200","status":"draft","tags":{"items":[]}}],"questions":{"items":[{"id":5,"statistics":{"difficulty":null,"rating":null},"resources":[{"language":"en","name":"What is the meaning of life?","attachments":{"before":{"items":[]},"after":{"items":[]},"during":{"items":[]}},"explanation":null}],"category":{"id":1,"name":"qsdf"},"delayMultiplier":1,"randomizeOptions":true,"multipleCorrect":false}]}}', true);
$success = json_decode ('{"id":3,"languages":[{"id":3,"language":"en"}],"category":{"id":1,"name":"qsdf"},"publisher":{"id":7,"name":"daedeloths quizzes","avatar":"https:\/\/www.gravatar.com\/avatar\/372b92179ff1e7351a11db02b2deaaba?d=retro&s=200","age":28,"statistics":{"joined_at":"2015-01-15T16:03:33+01:00","last_online":"2015-01-15T16:03:33+01:00","played":{"questions":100,"packs":5,"quizzes":1},"created":{"questions":100,"packs":5,"quizzes":1}}},"statistics":{"difficulty":null,"rating":null,"rounds":2,"questions":7},"resources":[{"language":"en","name":"A random round","image":"https:\/\/www.gravatar.com\/avatar\/qsdf?d=retro&s=200","status":"draft","tags":{"items":[]}}],"questions":{"items":[{"id":5,"statistics":{"difficulty":null,"rating":null},"resources":[{"language":"en","name":"What is the meaning of life?","options":{"items":[{"option":1,"text":"Aaaa","correct":true},{"option":2,"text":"Bbbb","correct":false},{"option":3,"text":"Cccc","correct":false},{"option":4,"text":"Dddd","correct":false}]},"attachments":{"before":{"items":[]},"after":{"items":[]},"during":{"items":[]}},"explanation":null}],"category":{"id":1,"name":"qsdf"},"delayMultiplier":1,"randomizeOptions":true,"multipleCorrect":false}]}}', true);

$result = $validator->validate ('TranslatedRoundInput', $success);

if ($result) {
	echo '<p><strong>Success!</strong></p>';
}
else {
	echo '<p><strong>Failure</strong></p>';
	foreach ($validator->getErrors () as $v) {
		echo '<p>' . $v . '</p>';
	}
}