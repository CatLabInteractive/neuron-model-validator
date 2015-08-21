<?php
namespace CatLab\Validator\Models;

use CatLab\Validator\Collections\ErrorCollection;
use CatLab\Validator\Collections\RequirementCollection;
use CatLab\Validator\Requirements\Equals;
use CatLab\Validator\Requirements\Requirement;

class Property {

	/**
	 * @param $name
	 * @param array|string $requirements
	 * @return \CatLab\Validator\Models\Property
	 */
	public static function make ($name, $requirements = null)
	{
		$property = new self ($name);

		if ($requirements) {
			if (!is_array ($requirements)) {
				$requirements = explode ('|', $requirements);
			}

			foreach ($requirements as $requirement) {

				$property->addRequirement (Requirement::getFromString ($requirement));
			}
		}

		return $property;
	}

	/** @var string $property */
	private $property;

	/** @var RequirementCollection $requirements */
	private $requirements;

	/** @var ErrorCollection $errors */
	private $errors;

	public function __construct ($name)
	{
		$this->property = $name;
		$this->requirements = new RequirementCollection ();
	}

	/**
	 * @return string
	 */
	public function getName ()
	{
		return $this->property;
	}

	/**
	 * @param Requirement $requirement
	 * @return $this
	 */
	public function addRequirement (Requirement $requirement)
	{
		$this->requirements[] = $requirement;
		return $this;
	}

	/**
	 * @param ErrorCollection $errors
	 * @return $this
	 */
	public function setErrors (ErrorCollection $errors)
	{
		$this->errors = $errors;
		return $this;
	}

	/**
	 * @param mixed $value
	 * @return Property
	 */
	public function setValue($value)
	{
		// Check if we already have a value
		$found = false;
		foreach ($this->requirements as $requirement) {
			if ($requirement instanceof Equals) {
				$requirement->setValue($value);
				$found = true;
			}
		}

		if (!$found) {
			$this->addRequirement(new Equals($value));
		}

		return $this;
	}

	public function clearValue()
	{
		$reqs = array();
		foreach ($this->requirements as $requirement) {
			if (! $requirement instanceof Equals) {
				$reqs[] = $requirement;
			}
		}
		$this->requirements = $reqs;
	}

	/**
	 * @param Model $model
	 * @param $data
	 * @return bool
	 */
	public function validate (Model $model, $data)
	{
		$okay = true;

		foreach ($this->requirements as $requirement) {
			if (!$requirement->validate ($data)) {
				$okay = false;
				$this->errors[] = new Error ($model, $this, $requirement);
			}
		}

		return $okay;
	}

}