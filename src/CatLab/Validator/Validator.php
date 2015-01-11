<?php
namespace CatLab\Validator;

use CatLab\Validator\Collections\ErrorCollection;
use CatLab\Validator\Collections\ModelCollection;
use CatLab\Validator\Exceptions\ModelAlreadyDefined;
use CatLab\Validator\Exceptions\ModelNotDefined;
use CatLab\Validator\Models\Error;
use CatLab\Validator\Models\Model;
use CatLab\Validator\Importer\Swagger;

class Validator {

	/**
	 * Set up a validation environment based on swagger documentation.
	 * @param $path
	 * @return \CatLab\Validator\Validator
	 */
	public static function fromSwagger ($path)
	{
		// Create importer
		$importer = new Swagger ($path);

		// Create validator
		$validator = new Validator ();

		// Run the importer
		$importer->run ($validator);

		return $validator;
	}

	/** @var Error[] $errors */
	private $errors;

	/** @var Model[] $models */
	private $models;

	public function __construct ()
	{
		$this->models = new ModelCollection ();
		$this->errors = new ErrorCollection ();
	}

	public function addModel (Model $model)
	{
		if (isset ($this->models[$model->getName ()]))
		{
			throw new ModelAlreadyDefined ("Model " . $model->getName () . " is already defined.");
		}

		// Set error container
		$model->setErrors ($this->errors);

		// Add the model.
		$this->models[$model->getName ()] = $model;
	}

	/**
	 * @param $modelName
	 * @return Model
	 * @throws ModelNotDefined
	 */
	public function getModel ($modelName)
	{
		if (!isset ($this->models[$modelName])) {
			throw new ModelNotDefined ("Model " . $modelName . " is not defined.");
		}

		return $this->models[$modelName];
	}

	/**
	 * @param $modelName
	 * @param $data
	 * @return bool
	 * @throws ModelNotDefined
	 */
	public function validate ($modelName, $data)
	{
		// Empty the error collection
		$this->errors->clear ();

		// Get the model
		$model = $this->getModel ($modelName);

		// And check.
		if ($model->validate ($data)) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Return all errors
	 * @return ErrorCollection
	 */
	public function getErrors ()
	{
		return clone $this->errors;
	}

}