<?php
namespace CatLab\Validator\Requirements;

use CatLab\Validator\Exceptions\UnsupportedFilter;
use CatLab\Validator\Models\Model;
use CatLab\Validator\Models\Property;

abstract class Requirement {

	/**
	 * @param $name
	 * @throws UnsupportedFilter
	 * @return \CatLab\Validator\Requirements\Requirement
	 */
	public static function getFromString ($name)
	{
		$parts = explode (':', $name);

		switch (strtolower ($parts[0]))
		{
			case 'required':
				return new Exists ();
			break;

			case 'notempty':
				return new NotEmpty ();
			break;

			case 'numeric':
				return new IsType ('numeric');
			break;

			case 'string':
				return new IsType ('string');
			break;

			case 'min':
				if (!isset ($parts[1]) || is_numeric ($parts[1]))
				{
					throw new UnsupportedFilter ("Filter " . $parts[1] . " has an invalid value.");
				}

				return new IsMin ($parts[1]);
			break;

			case 'max':
				if (!isset ($parts[1]) || is_numeric ($parts[1]))
				{
					throw new UnsupportedFilter ("Filter " . $parts[1] . " has an invalid value.");
				}

				return new IsMax ($parts[1]);
			break;

			default:
				throw new UnsupportedFilter ("Filter " . $parts[0] . " is not supported.");
			break;
		}
	}

	/**
	 *
	 */
	public function __construct ()
	{

	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function validate ($value)
	{
		return true;
	}

	/**
	 * @param Model $model
	 * @param Property $property
	 * @return string
	 */
	protected function getPropertyName (Model $model, Property $property)
	{
		return $model->getPrefix () . $property->getName ();
	}

	/**
	 * @param Model $model
	 * @param Property $property
	 * @return string
	 */
	public function getError (Model $model, Property $property)
	{
		return 'Property "' . $this->getPropertyName ($model, $property) . '" does not match requirement ' . get_class ($this);
	}

}