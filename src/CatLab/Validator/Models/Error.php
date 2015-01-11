<?php
namespace CatLab\Validator\Models;

use CatLab\Validator\Models\Requirements\Requirement;

class Error {

	/** @var Property $property */
	private $property;

	/** @var Requirement $requirement */
	private $requirement;

	/** @var string $error */
	private $error;

	public function __construct (Property $property, Requirement $requirement)
	{
		$this->setProperty ($property);
		$this->setRequirement ($requirement);
	}

	/**
	 * @return Property
	 */
	public function getProperty ()
	{
		return $this->property;
	}

	/**
	 * @param Property $property
	 * @return $this
	 */
	private function setProperty ($property)
	{
		$this->property = $property;
		return $this;
	}

	/**
	 * @return Requirement
	 */
	public function getRequirement ()
	{
		return $this->requirement;
	}

	/**
	 * @param Requirement $requirement
	 * @return $this
	 */
	public function setRequirement ($requirement)
	{
		$this->requirement = $requirement;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getError ()
	{
		return $this->requirement->getError ($this->getProperty ());
	}

	/**
	 * @return string
	 */
	public function __toString ()
	{
		return $this->error;
	}

}