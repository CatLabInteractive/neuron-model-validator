# Validator
A php input validator that can handle Swagger V1 specs.

Example usage:
--------------
```php
$validator = \CatLab\Validator\Validator::fromSwagger('specs/');

$input = array ('id' => 1, 'name' => 'Example');

if ($validator->validate('ModelId', $input)) {
	echo 'Success!';
}
else {
	echo 'Failure' . "\n";
	foreach ($validator->getErrors() as $v) {
		echo $v . "\n";
	}
}
```

Without swagger:
----------------
Regular models:
```php
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

$validator = new Validator();
$validator->addModel(Model::make('test', $specs));

$valid = $validator->validate('test', $data);
```

Optional attributes:
Append a question mark to your property name to mark a property as optional.
```php
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

$validator = new Validator();
$validator->addModel(Model::make('test', $specs));

$correct = $validator->validate('test', $data);
```

Advanced examples:
------------------
Arrays:
```php
$validator = new Validator();

$model = Model::make(
    'ArrayModel',
    array (
        'id' => 'required|int',
        'collection' => array (
            'count' => 'int|required',
            'items[]?' => 'int'
        )
    )
);

$data = array (
    'id' => 1,
    'collection' => array (
        'count' => 2,
        'items' => array (1, 2, 3)
    )
);

$correct = $validator->validate('ArrayModel', $data);

$validator->addModel($model);
```

Arrays of models:
```php
$validator = new Validator();

$model = Model::make(
    'ArrayModel',
    array (
        'id' => 'required|int',
        'collection' => array (
            'count' => 'int|required',
            'items[]?' => array (
                'id' => 'int|required',
                'name' => 'string'
            )
        )
    )
);

$validator->addModel($model);

$data = array (
    'id' => 1,
    'collection' => array (
        'count' => 2,
        'items' => array (
            array (
                'id' => 15,
                'name' => 'Foo'
            ),

            array (
                'id' => 16,
                'name' => 'Bar'
            )
        )
    )
);

$correct = $validator->validate('ArrayModel', $data);
```

Put a question mark at the end of your property name to mark the array property as optional.
```php
$validator = new Validator();

$model = Model::make(
    'ArrayModel',
    array (
        'id' => 'required|int',
        'collection' => array (
            'count' => 'int|required',
            'items[]?' => array (
                'id' => 'int|required',
                'name' => 'string'
            )
        )
    )
);

$validator->addModel($model);

$correctData = array (
    'id' => 1,
    'collection' => array (
        'count' => 2,
        'items' => array (
            array (
                'id' => 15,
                'name' => 'Foo'
            ),

            array (
                'id' => 16,
                'name' => 'Bar'
            )
        )
    )
);

$correct = $validator->validate('ArrayModel', $correctData);
```

Usage with PHPUnit:
-------------------
```php
$model = Model::make(
    'ModelWithValues',
    array (
        'id' => 'required|string',
        'name' => 'required|string',
        'value1' => 'required|string'
    )
);

$data = array (
    'id' => 'I am a string',
    'name' => 'My name is Paul',
    'value1' => 'I\'m very happy here'
);

$this->assertThat($data, new IsValidModel($model));
```

Checking for exact values (with conversion to comparable types):
----------------------------------------------------------------
```php
$validator = new Validator();

$model = Model::make(
    'ModelWithValues',
    array (
        'id' => 'required|string',
        'name' => 'required|string',
        'value1' => 'required|string'
    )
);

$validator->addModel($model);

// Set the expected values
$model->setValues(array (
    'id' => 15,
    'name' => 'Thijs',
    'value1' => 'woop'
));

// Input data
$data = array (
    'id' => 15,
    'name' => 'Thijs',
    'value1' => 'woop'
);

// Check if model match expectations & compare values
$correct = $validator->validate ('ModelWithValues', $data);
```

