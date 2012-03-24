<?php
namespace ZfcBase\Entity;

use ZfcBase\Entity\Exception\BadMethodCall as EntityBadMethodCallException;

abstract class Entity
{

    /**
     * @param mixed $data
     */
    public function __construct($data=null)
    {
        if($data instanceof \stdClass)
        {
            $this->setFromStdClass($data);
        }

        if(is_array($data))
        {
            $this->setFromArray($data);
        }
    }

    /**
     * Gets a parameter value
     * @param $name string
     * @return mixed
     * @throws EntityBadMethodCallException
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        throw new EntityBadMethodCallException("Property by name {$name} does not exist");
    }

    /**
     * Sets a parameter value
     * @param $name string
     * @param $value mixed
     * @return Graph
     * @throws EntityBadMethodCallException
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            $this->{$method}($value);
            return $this;
        }

        if (property_exists($this, $name)) {
            $this->{$name} = $value;
            return $this;
        }
        throw new EntityBadMethodCallException("Property by name {$name} does not exist");
    }

    /**
     * Sets an array of parameters
     * @param array $array
     * @return Graph
     */
    protected function setFromArray(array $array)
    {
        foreach ($array as $key => $value) {
            try {
                $this->__set($key, $value);
            } Catch (GraphException $e) {
                //Skip missing properties
                continue;
            }
        }
        return $this;
    }

    /**
     * Sets properies from a stdClass
     * @param \stdClass $class
     * @return Entity
     */
    protected function setFromStdClass(\stdClass $class)
    {
        foreach($class as $key=>$value)
        {
            try {
                $this->__set($key, $value);
            } Catch (GraphException $e) {
                //Skip missing properties
                continue;
            }
        }
        return $this;
    }

}
