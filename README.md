# Validator
A php input validator that can handle Swagger V1 specs.

Example usage:
-------------
```php
$validator = Validator::fromSwagger ('specs/');

$input = array ('id' => 1, 'name' => 'Example');
$result = $validator->validate ('ModelId', $input);

if ($result) {
	echo '<p><strong>Success!</strong></p>';
}
else {
	echo '<p><strong>Failure</strong></p>';
	foreach ($validator->getErrors () as $v) {
		echo '<p>' . $v . '</p>';
	}
}
```
