<?php

namespace CatLab\Validator\Models;

use CatLab\Validator\Collections\ErrorCollection;
use CatLab\Validator\Collections\PropertyCollection;
use CatLab\Validator\Exceptions\PropertyNotDefined;
use CatLab\Validator\Requirements\Exists;

class Model {

	/**
	 * @param $name
	 * @param array $properties
	 * @param string $prefix
	 * @return \CatLab\Validator\Models\Model
	 */
	public static function make ($name, array $properties, $prefix = null)
	{
		$model = new self ($name);

		if ($prefix)
		{
			$model->setPrefix ($prefix);
		}

		$model->processProperties ($properties);

		return $model;
	}

	/** @var string $name */
	private $name;

	/** @var Property[] $property */
	private $properties;

	/** @var Property[] $propertymap */
	private $propertyMap;

	/** @var ErrorCollection $errors */
	private $errors;

	/** @var string $prefix */
	private $prefix;

	/**
	 * @param $name
	 */
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

		$this->propertyMap[$property->getName()] = $property;

		return $this;
	}

	/**
	 * @param $name
	 * @return Property
	 * @throws PropertyNotDefined
	 */
	public function getProperty($name)
	{
		if (!isset ($this->propertyMap[$name])) {
			throw new PropertyNotDefined("Property with name {$name} is not defined.");
		}

		return $this->propertyMap[$name];
	}

	/**
	 * Force an Equals check on a property value
	 * @param $property
	 * @param $value
	 * @return $this
	 */
	public function setValue($property, $value)
	{
		$property = $this->getProperty($property);
		$property->setValue($value);

		return $this;
	}

	/**
	 * @param $values
	 * @return $this
	 */
	public function setValues($values)
	{
		foreach ($values as $k => $v) {
			$this->setValue($k, $v);
		}
		return $this;
	}

	/**
	 * Clear values
	 * @return $this
	 */
	public function clearValues()
	{
		foreach ($this->properties as $property) {
			$property->clearValue();
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

			if (!$property->validate ($this, $value)) {
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

	/**
	 * Process properties input
	 */
	private function processProperties (array $properties)
	{
		foreach ($properties as $propertyName => $property)
		{
			$this->processProperty ($propertyName, $property);
		}
	}

	private function processProperty ($name, $property)
	{
		// Now this could be two things, a list of filters or a submodel.
		if (is_array ($property))
		{
			if (isset ($property[0]))
			{
				// Property name is numeric; this is a filter.
				// Make a property and continue.
				$this->addProperty (Property::make ($name, $property));
			}

			else {

				// Is this model optional?
				if (substr ($name, -1) === '?')
				{
					$name = substr ($name, 0, -1);

					$prefix = $this->getPrefix () . $name . '.';

					$model = self::make ($name, $property, $prefix);
					$prop = new ModelProperty ($model);
				}
				else {

					$prefix = $this->getPrefix () . $name . '.';

					$model = self::make ($name, $property, $prefix);

					$prop = new ModelProperty ($model);
					$prop->addRequirement (new Exists ());
				}

				$this->addProperty ($prop);
			}
		}

		else {
			$this->addProperty (Property::make ($name, $property));
		}
	}

	/**
	 * @return string
	 */
	public function getPrefix ()
	{
		return $this->prefix;
	}

	/**
	 * @param string $prefix
	 */
	public function setPrefix ($prefix)
	{
		$this->prefix = $prefix;
		return $this;
	}
}