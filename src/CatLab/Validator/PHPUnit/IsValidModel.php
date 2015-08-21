<?php

namespace CatLab\Validator\PHPUnit;

use CatLab\Validator\Models\Model;
use CatLab\Validator\Validator;

class IsValidModel extends \PHPUnit_Framework_Constraint
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function matches($other)
    {
        $this->validator = new Validator();
        $this->validator->addModel($this->model);

        $valid = $this->validator->validate($this->model->getName(), $other);

        return $valid;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'is valid model structure';
    }
}