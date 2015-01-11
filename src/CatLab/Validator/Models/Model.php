<?php

namespace CatLab\Validator\Models;

use CatLab\Validator\Collections\ErrorCollection;
use CatLab\Validator\Collections\PropertyCollection;

class Model {

	public static function make ($name, $properties)
	{

	}

	/** @var string $name */
	private $name;

	/** @var Property[] $property */
	private $properties;

	/** @var ErrorCollection $errors */
	private $errors;

	public function __construct ($name)
	{
		$this->setName ($name);

		$this->properties = new PropertyCollection ();
	}

	/**
	 * @param ErrorCollection $errors
	 * @return $this
	 */
	public function setErrors (ErrorCollection $errors)
	{
		$this->errors = $errors;

		foreach ($this->properties as $property) {
			$property->setErrors ($this->errors);
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	private function setName ($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param Property $property
	 * @return $this
	 */
	public function addProperty (Property $property)
	{
		$this->properties[] = $property;

		if (isset ($this->errors)) {
			$property->setErrors ($this->errors);
		}

		return $this;
	}

	/**
	 * @param $data
	 * @return bool
	 */
	public function validate ($data)
	{
		$okay = true;

		foreach ($this->properties as $property)
		{
			$value = isset ($data[$property->getName ()]) ? $data[$property->getName ()] : null;

			if (!$property->validate ($value)) {
				$okay = false;
			}
		}

		return $okay;
	}

	/**
	 * @param $data
	 * @return bool
	 */
	public function passes ($data)
	{
		return $this->validate ($data);
	}

	/**
	 * @param $data
	 * @return bool
	 */
	public function fails ($data)
	{
		return !$this->validate ($data);
	}
}