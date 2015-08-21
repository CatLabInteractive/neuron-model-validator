<?php
namespace CatLab\Validator\Requirements;
use CatLab\Validator\Exceptions\TranslateValueException;
use CatLab\Validator\Models\Property;

/**
 * Class Equals
 *
 * Special requirement that checks if value equals a given ... value
 *
 * @package CatLab\Validator\Requirements
 */
class Equals extends Requirement {

	/**
	 * @var mixed
	 */
	private $value;

	/**
	 * @param $value
	 */
	public function __construct ($value)
	{
		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param mixed $value
	 * @return Equals
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * @param $value
	 * @return \DateTime
	 * @throws TranslateValueException
	 */
	private function convertToDatetime($value)
	{
		if ($value instanceof \DateTime) {
			return $value;
		}

		elseif (is_numeric($value)) {
			$date = new \DateTime();
			$date->setTimestamp($value);

			return $date;
		}

		elseif ($value = new \DateTime($value)) {
			return $value;
		}

		else {
			throw new TranslateValueException("Could not convert to datetime");
		}

	}

	/**
	 * Convert values for comparison
	 * @param $value
	 * @param $type
	 * @return \DateTime
	 * @throws TranslateValueException
	 */
	private function convertValue($value, $type)
	{
		switch ($type)
		{
			case 'datetime':
				return $this->convertToDatetime($value);

			default:
				return $value;
		}
	}

	/**
	 * @param $value
	 * @param Property $property
	 * @return bool
	 */
	public function validate ($value, Property $property)
	{
		// Guess the type
		$type = $property->getType();
		return $this->convertValue($this->value, $type) == $this->convertValue($value, $type);
	}

}