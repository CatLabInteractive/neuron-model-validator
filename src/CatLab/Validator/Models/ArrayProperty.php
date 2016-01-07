<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 11/01/15
 * Time: 17:30
 */

namespace CatLab\Validator\Models;

use CatLab\Validator\Collections\ErrorCollection;
use CatLab\Validator\Requirements\IsType;
use Traversable;

class ArrayProperty
	extends Property {

	/** @var Property $model */
	private $childProperty = null;

	public function __construct ($name, Property $childType = null)
	{
		$this->childProperty = $childType;
		parent::__construct ($name);

        // Must be traversable
        $this->addRequirement(new IsType(IsType::TYPE_ARRAY));
	}

	public function setErrors (ErrorCollection $errors)
	{
		parent::setErrors ($errors);
		$this->childProperty->setErrors ($errors);
	}

	public function validate (Model $model, $data)
	{
		// First validate the base
		if (!parent::validate ($model, $data)) {
			return false;
		}

		if (isset ($data) && isset ($this->childProperty)) {

			$okay = true;
			foreach ($data as $v) {
				if (!$this->childProperty->validate ($model, $v)) {
					$okay = false;
				}
			}

			return $okay;
		}
		else {
			return true;
		}
	}

}