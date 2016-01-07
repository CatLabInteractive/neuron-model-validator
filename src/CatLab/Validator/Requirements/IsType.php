<?php
namespace CatLab\Validator\Requirements;

use CatLab\Validator\Models\Model;
use CatLab\Validator\Models\Property;
use Traversable;

class IsType
	extends Requirement {

	const TYPE_ARRAY = 'array';

    const TYPE_TEXT = 'text';
    const TYPE_BOOL = 'bool';
    const TYPE_STRING = 'string';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_INT = 'int';

    const TYPE_PASSWORD = 'password';
    const TYPE_EMAIL = 'email';
    const TYPE_URL = 'url';

	/**
	 * @var string
	 */
	private $type;

	public function __construct ($type)
	{
		$this->type = $type;
	}

	public static function checkInput ($value, $type)
	{
		if ($type == self::TYPE_ARRAY)
		{
			return is_array($value) || $value instanceof Traversable;
		}

		else if ($type == self::TYPE_TEXT)
		{
			return true;
		}

		else if ($type == self::TYPE_BOOL || $type == 'boolean')
		{
			return $value === true || $value === false || $value === 1 || $value === 0;
		}

		elseif ($type == 'varchar' || $type == self::TYPE_STRING)
		{
			return self::isValidUTF8 ($value);
		}

		elseif ($type == self::TYPE_PASSWORD)
		{
			return self::isValidUTF8 ($value) && strlen ($value) > 2;
		}

		elseif ($type == self::TYPE_EMAIL)
		{
			//return (bool)preg_match("/^[_a-z0-9-]+(\.[_a-z0-9\+\-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $value);
			return self::isValidUTF8 ($value) && filter_var ($value, FILTER_VALIDATE_EMAIL) ? true : false;
		}

		elseif ($type == 'username')
		{
			return self::isValidUTF8 ($value) && (bool)preg_match ('/^[a-zA-Z0-9_]{3,20}$/', $value);
		}

		elseif ($type == self::TYPE_DATE)
		{
			$time = explode ('-', $value);
			return self::isValidUTF8 ($value) && (count ($time) == 3);
		}

		elseif ($type == self::TYPE_DATETIME)
		{
			if (is_numeric($value)) {
				return true;
			}
			else {
				try {
					$timestamp = new \DateTime($value);
					return $timestamp !== false;
				}
				catch (\Exception $e) {
					return false;
				}
			}
		}

		elseif ($type == 'md5')
		{
			return self::isValidUTF8 ($value) && strlen ($value) == 32;
		}

		elseif ($type == self::TYPE_URL)
		{
			$regex = '/((https?:\/\/|[w]{3})?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/i';
			return self::isValidUTF8 ($value) && (bool)preg_match($regex, $value);

			/*

			$regex = "((https?|ftp)\:\/\/)?"; // Scheme
			$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
			$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
			$regex .= "(\:[0-9]{2,5})?"; // Port
			$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
			$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
			$regex .= "(#[a-z_.\!-][a-z0-9+\$_.\!-]*)?"; // Anchor

			return (bool)preg_match("/^$regex$/i", $value);
			*/

			//return filter_var ($value, FILTER_VALIDATE_URL) !== false;

			//return (bool)preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value);
		}

		elseif ($type == 'number' || $type == self::TYPE_NUMERIC || $type == 'float')
		{
			return is_numeric ($value);
		}

		elseif ($type == self::TYPE_INT || $type == 'integer')
		{
			return is_numeric ($value) && (int)$value == $value;
		}

		else {

			return false;
			//echo 'fout: '.$type;

		}
	}

	/**
	 * Check if a string is valid UTF8
	 * @param $str
	 * @return bool
	 */
	public static function isValidUTF8 ($str)
	{
		return (bool) preg_match('//u', $str);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param $value
	 * @param Property $property
	 * @return bool
	 */
	public function validate ($value, Property $property)
	{
		if (!isset ($value))
			return true;

		return self::checkInput ($value, $this->type);
	}

	/**
	 * @param Model $model
	 * @param Property $property
	 * @return string
	 */
	public function getError (Model $model, Property $property)
	{
		$error = parent::getError ($model, $property);

		$error .= ' (' . $this->type . ')';

		return $error;
	}
}