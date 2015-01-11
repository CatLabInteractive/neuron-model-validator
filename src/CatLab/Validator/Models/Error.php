<?php
namespace CatLab\Validator\Models;

use CatLab\Validator\Requirements\Requirement;

class Error {

	/** @var Property $property */
	private $property;

	/** @var Requirement $requirement */
	private $requirement;

	/** @var Model $model */
	private $model;

	public function __construct (Model $model, Property $property, Requirement $requirement)
	{
		$this->setModel ($model);
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
	 * @return Model
	 */
	public function getModel ()
	{
		return $this->model;
	}

	/**
	 * @param Model $model
	 */
	public function setModel ($model)
	{
		$this->model = $model;
	}

	/**
	 * @return string
	 */
	public function getError ()
	{
		return $this->requirement->getError ($this->getModel (), $this->getProperty ());
	}

	/**
	 * @return string
	 */
	public function __toString ()
	{
		return $this->getError ();
	}

}