<?php

namespace Ddeboer\DocumentManipulationBundle\Document;

use Ddeboer\DocumentManipulationBundle\File\File;

/**
 * A collection of key/value pairs that contains mail merge
 */
class DocumentData implements \IteratorAggregate, \Countable, \ArrayAccess
{
    protected $values = array();

    public function __construct(array $values = null)
    {
        if ($values) {
            $this->addAll($values);
        }
    }

    public function set($key, $value)
    {
        if (!\is_scalar($value) && !$value instanceof File) {
            if (\is_array($value)) {
                foreach ($value as $blockKey => $blockValue) {
                    if (!\is_array($blockValue)) {
                        throw new \InvalidArgumentException(
                            'Block ' . $blockKey . ' must be an array'
                        );
                    }
                }
            } else {
                throw new \InvalidArgumentException(
                    'Value must be scalar, file or merge block array'
                );
            }

        }

        $this->values[$key] = $value;

        return $this;
    }

    public function addAll(array $values)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function remove($key)
    {
        unset($this->$key);
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }
    }

    /**
     * Get context key value pairs as replacements
     *
     * @param string $wrapIn    String that each key must be wrapped in
     * @return array            Replacement key value pairs
     */
    public function getReplacements($wrapIn = '%')
    {
        $replacements = array();

        foreach ($this->values as $key => $value) {
            $replacements[$wrapIn . $key . $wrapIn] = $value;
        }

        return $replacements;
    }

    public function count()
    {
        return count($this->values);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function merge(self $context)
    {
        foreach ($context as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Replace placeholders in any text with values from this context
     *
     * @param string $text
     * @param string $wrapIn  Symbol that surrounds placeholders, e.g. %name%
     * @return string         The text with replacements applied
     */
    public function replace($text, $wrapIn = '%')
    {
        return strtr($text, $this->getReplacements());
    }

    /**
     * Get context values as array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->values;
    }
}