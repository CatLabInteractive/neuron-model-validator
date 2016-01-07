<?php

use CatLab\Validator\Models\Model;
use CatLab\Validator\Validator;

require '../vendor/autoload.php';

$validator = new Validator();

$model = Model::make(
    'ArrayModel',
    array(
        'id' => 'required|int',
        'collection' => array(
            'count' => 'int|required',
            'items[]' => 'int'
        )
    )
);

$validator->addModel($model);

$correctData = array(
    'id' => 1,
    'collection' => array(
        'count' => 2,
        'items' => array(1, 2, 3)
    )
);

var_dump($validator->validate('ArrayModel', $correctData));