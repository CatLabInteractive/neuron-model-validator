<?php
namespace CatLab\Validator\Models;

use CatLab\Validator\Collections\ErrorCollection;
use CatLab\Validator\Collections\RequirementCollection;
use CatLab\Validator\Requirements\Requirement;

class Property {

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

	public function validate ($data)
	{
		$okay = true;

		foreach ($this->requirements as $requirement) {
			if (!$requirement->validate ($data)) {
				$okay = false;
				$this->errors[] = new Error ($this, $requirement);
			}
		}

		return $okay;
	}

}