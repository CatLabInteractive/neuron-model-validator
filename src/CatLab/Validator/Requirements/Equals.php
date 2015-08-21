<?php
namespace CatLab\Validator\Requirements;

/**
 * Class Equals
 *
 * Special requirement that checks if value equals a given ... value
 *
 * @package CatLab\Validator\Requirements
 */
class Equals
	extends Requirement {

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
	 * @return bool
	 */
	public function validate ($value)
	{
		return $this->value == $value;
	}

}