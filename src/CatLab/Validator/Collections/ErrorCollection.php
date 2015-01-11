<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 11/01/15
 * Time: 15:43
 */

namespace CatLab\Validator\Collections;


class ErrorCollection
	extends Collection {

	/**
	 * Get all errors that are related to a given property.
	 * @param $name
	 * @return array|ErrorCollection
	 */
	public function property ($name)
	{
		$collection = new self ();

		foreach ($this as $error)
		{
			if ($error->getProperty ()->getName () == $name)
			{
				$collection[] = $error;
			}
		}

		return $collection;
	}

}