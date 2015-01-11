<?php
namespace CatLab\Validator\Requirements;

class IsType
	extends Requirement {

	private $type;

	public function __construct ($type)
	{
		$this->type = $type;
	}

	public static function checkInput ($value, $type)
	{
		if ($type == 'text')
		{
			return true;
		}

		else if ($type == 'bool')
		{
			return $value == 1 || $value == 'true';
		}

		elseif ($type == 'varchar' || $type == 'string')
		{
			return self::isValidUTF8 ($value);
		}

		elseif ($type == 'password')
		{
			return self::isValidUTF8 ($value) && strlen ($value) > 2;
		}

		elseif ($type == 'email')
		{
			//return (bool)preg_match("/^[_a-z0-9-]+(\.[_a-z0-9\+\-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $value);
			return self::isValidUTF8 ($value) && filter_var ($value, FILTER_VALIDATE_EMAIL) ? true : false;
		}

		elseif ($type == 'username')
		{
			return self::isValidUTF8 ($value) && (bool)preg_match ('/^[a-zA-Z0-9_]{3,20}$/', $value);
		}

		elseif ($type == 'date')
		{
			$time = explode ('-', $value);
			return self::isValidUTF8 ($value) && (count ($time) == 3);
		}

		elseif ($type == 'md5')
		{
			return self::isValidUTF8 ($value) && strlen ($value) == 32;
		}

		elseif ($type == 'url')
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

		elseif ($type == 'number' || $type == 'numeric')
		{
			return is_numeric ($value);
		}

		elseif ($type == 'int')
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

	public function validate ($value)
	{
		if (!isset ($value))
			return true;

		return self::checkInput ($value, $this->type);
	}

}