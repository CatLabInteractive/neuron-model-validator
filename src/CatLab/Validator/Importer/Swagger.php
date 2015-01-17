<?php

namespace CatLab\Validator\Importer;

use CatLab\Validator\Exceptions\ModelNotDefined;
use CatLab\Validator\Models\ArrayProperty;
use CatLab\Validator\Models\Model;
use CatLab\Validator\Models\ModelProperty;
use CatLab\Validator\Models\Property;
use CatLab\Validator\Requirements\Exists;
use CatLab\Validator\Requirements\IsArray;
use CatLab\Validator\Requirements\IsType;
use CatLab\Validator\Validator;

class Swagger {

	private $path;
	private $library;

	public function __construct ($path)
	{
		$this->setPath ($path);
		$this->library = array ();
	}

	public function setPath ($path)
	{
		if (substr ($path, -13) == 'api-docs.json')
		{
			$path = substr ($path, 0, -13);
		}

		if (substr ($path, -1) == '/')
		{
			$path = substr ($path, 0, -1);
		}

		$this->path = $path;
	}

	public function run (Validator $validator)
	{
		// Fetch the root file
		$root = $this->path . '/api-docs.json';

		$rootfile = file_get_contents ($root);
		$rootfile = json_decode ($rootfile, true);

		foreach ($rootfile['apis'] as $api)
		{
			$this->processAPI ($api);
		}

		$this->processLibrary ($validator);
	}

	private function processAPI ($api)
	{
		$filepath = $api['path'];
		$filepath = str_replace ('{format}', 'json', $filepath);

		$content = file_get_contents ($this->path . $filepath);
		$content = json_decode ($content, true);

		if (isset ($content['models'])) {
			foreach ($content['models'] as $model) {
				$this->library[$model['id']] = $model;
			}
		}
	}

	private function processLibrary (Validator $validator)
	{
		foreach ($this->library as $id => $model)
		{
			//$this->processModel ($validator, $model);
			$validator->addModel ($this->getModel ($id));
		}
	}

	private function getModel ($id, $prefix = null)
	{
		if (!isset ($this->library[$id]))
			throw new ModelNotDefined ("Model " . $id . " was not found in swagger specifications.");

		$data = $this->library[$id];

		$model = new Model ($data['id']);
		$model->setPrefix ($prefix);

		/** @var Property[] $properties */
		$properties = array ();

		if (isset ($data['properties'])) {
			foreach ($data['properties'] as $propName => $property) {
				if (!empty ($property['$ref']))
				{
					$subModel = $this->getModel ($property['$ref'], $model->getPrefix () . $property['$ref'] . '.');

					$properties[$propName] = new ModelProperty ($subModel, $propName);
					$model->addProperty ($properties[$propName]);

				} else if (isset ($property['$ref'])) {
					// Do nothing.
					throw new \InvalidArgumentException ("Empty reference found.");
				}

				else if (isset ($property['type']) && strtolower ($property['type']) == 'array')
				{
					// Is array specified?
					if (isset ($property['items']))
					{
						if (isset ($property['items']['$ref']))
						{
							$subModel = $this->getModel ($property['items']['$ref'], $model->getPrefix () . $property['items']['$ref'] . '[].');
							$childProperty = new ModelProperty ($subModel, $propName);
						}

						else if (isset ($property['items']['type'])) {
							$childProperty = new Property ($propName);
							$childProperty->addRequirement (new IsType ($property['items']['type']));
						}
						else {
							throw new \InvalidArgumentException ('Array without type or $ref found.');
						}

						// Nope? Regular array.
						$properties[$propName] = new ArrayProperty ($propName, $childProperty);
						$model->addProperty ($properties[$propName]);
						$properties[$propName]->addRequirement (new IsArray ());
					}
					else {
						// Nope? Regular array.
						$properties[$propName] = new Property ($propName);
						$model->addProperty ($properties[$propName]);
						$properties[$propName]->addRequirement (new IsArray ());
					}
				}

				else {
					$properties[$propName] = new Property ($propName);
					$model->addProperty ($properties[$propName]);

					if (isset ($property['type'])) {
						$properties[$propName]->addRequirement (new IsType ($property['type']));
					}
				}
			}
		}

		// Requirements
		if (isset ($data['required'])) {
			foreach ($data['required'] as $v) {
				$properties[$v]->addRequirement (new Exists ());
			}
		}

		return $model;
	}

}