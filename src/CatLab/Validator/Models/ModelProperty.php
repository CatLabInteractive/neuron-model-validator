<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 11/01/15
 * Time: 17:30
 */

namespace CatLab\Validator\Models;


use CatLab\Validator\Collections\ErrorCollection;

class ModelProperty
	extends Property {

	/** @var Model $model */
	private $model;

	public function __construct (Model $model, $name = null)
	{
		$this->model = $model;

		if (!isset ($name))
			$name = $model->getName ();

		parent::__construct ($name);
	}

	public function setErrors (ErrorCollection $errors)
	{
		parent::setErrors ($errors);
		$this->model->setErrors ($errors);
	}

	public function validate (Model $model, $data)
	{
		// First validate the base
		if (!parent::validate ($model, $data)) {
			return false;
		}

		if (isset ($data)) {
			return $this->model->validate ($data);
		}
		else {
			return true;
		}
	}

}