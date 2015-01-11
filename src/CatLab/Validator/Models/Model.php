<?php

namespace CatLab\Validator\Models;

class Model {

	public static function make ($name, $properties)
	{

	}

	/** @var string $name */
	private $name;

	/** @var Property[] $property */
	private $properties;

	public function __construct ($name)
	{
		$this->setName ($name);
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
	 */
	private function setName ($name)
	{
		$this->name = $name;
	}

	public function validate ($data)
	{
		return true;
	}
}