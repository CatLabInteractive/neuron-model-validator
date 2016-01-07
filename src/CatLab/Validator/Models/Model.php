<?php

namespace CatLab\Validator\Models;

use CatLab\Validator\Collections\ErrorCollection;
use CatLab\Validator\Collections\PropertyCollection;
use CatLab\Validator\Exceptions\PropertyNotDefined;
use CatLab\Validator\Requirements\Exists;

/**
 * Class Model
 * @package CatLab\Validator\Models
 */
class Model
{

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
		foreach ($properties as $propertyName => $property) {
			$this->processProperty ($propertyName, $property);
		}
	}

    /**
     * Process model properties
     * @param $name
     * @param $property
     */
	private function processProperty ($name, $property)
	{
		$this->addProperty($this->getPropertyFromInput($name, $property));
	}

    /**
     * @param $name
     * @param $property
     * @return Property
     */
    private function getPropertyFromInput($name, $property)
    {
        // Is this property an ArrayProperty?
        if (
            substr($name, -2) === '[]' ||
            substr($name, -3) === '[]?'
        ) {
            return $this->getArrayPropertyFromInput($name, $property);
        }

        // Now this could be two things, a list of filters or a submodel.
        else if (is_array($property)) {

            // If plain array, (not assoc), this is a list of filters(
            if (isset($property[0])) {
                // Property name is numeric; this is a filter.
                // Make a property and continue.
                return Property::make($name, $property);
            }

            // If a map, this is a list of properties
            else {
                // Is this model optional or required?
                $required = true;
                if (substr ($name, -1) === '?')
                {
                    $name = substr ($name, 0, -1);
                    $required = false;
                }

                // Create a model property (with a nice prefix)
                $prefix = $this->getPrefix() . $name . '.';

                // Make the model
                $model = self::make($name, $property, $prefix);

                // Make the model property
                $prop = new ModelProperty($model);

                // Remember when we checked for the "?"
                if ($required) {
                    $prop->addRequirement(new Exists());
                }

                // Return the model property
                return $prop;
            }
        }

        // Very simple string property (= list of filters)
        else {
            return Property::make($name, $property);
        }
    }

    /**
     * @param string $name
     * @param mixed
     * @return ArrayProperty
     */
    private function getArrayPropertyFromInput($name, $property)
    {
        // Is this model optional or required?
        $required = true;
        if (substr ($name, -1) === '?')
        {
            $name = substr ($name, 0, -1);
            $required = false;
        }

        $name = substr($name, 0, -2);

        // The child property is just the base property without the []
        $childProperty = $this->getPropertyFromInput($name, $property);

        // Make a property.
        $property = new ArrayProperty($name, $childProperty);

        if ($required) {
            $property->addRequirement(new Exists());
        }

        return $property;
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
     * @return Model
	 */
	public function setPrefix ($prefix)
	{
		$this->prefix = $prefix;
		return $this;
	}
}