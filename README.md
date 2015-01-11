# Validator
A php input validator that can handle Swagger V1 specs.

Example usage:
-------------
```php
$validator = Validator::fromSwagger ('specs/');

$input = array ('id' => 1, 'name' => 'Example');

if ($validator->validate ('ModelId', $input)) {
	echo 'Success!';
}
else {
	echo 'Failure' . "\n";
	foreach ($validator->getErrors () as $v) {
		echo $v . "\n";
	}
}
```
