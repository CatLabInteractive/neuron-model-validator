<?php

namespace CatLab\Validator\Collections;

/**
 *
 */
class ErrorCollection extends \Neuron\Collections\ErrorCollection
{
    /**
     * Get all errors that are related to a given property.
     * @param $name
     * @return array|ErrorCollection
     */
    public function property($name)
    {
        $collection = new self ();

        foreach ($this as $error) {
            if ($error->getProperty()->getName() == $name) {
                $collection[] = $error;
            }
        }

        return $collection;
    }

}
