<?php
namespace ZfcBase\Collection;

use ZfcBase\Collection\Exception\BadMethodCall,
    ZfcBase\Entity\Entity;

abstract class Collection implements \Iterator, \Countable, \ArrayAccess
{
    /**
     * Entity Class
     * @var string
     */
    protected $entity;

    /**
     * Current Position
     * @var int
     */
    protected $position = 0;

    /**
     * Data
     * @param array
     */
    protected $data = array();

    /**
     * Constructor
     * @param array|null $data
     * @return \ZfcBase\Collection\Collection
     */
    public function __construct(array $data = null)
    {
        if(empty($this->entity))
        {
            throw new BadMethodCall('Property $entity must be set in child class');
        }
        if ($data !== null)
        {
            $this->data = $data;
        }
    }

    /**
     * Count
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Current
     * @return ZfcEntityCollection\Entity\Entity
     */
    public function current()
    {
        return $this->buildEntity($this->position);
    }

    /**
     * Key
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Next
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Rewind
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Valid
     * @return bool
     */
    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    /**
     * Builds Entity
     * @param $position
     * @return Entity
     */
    protected function buildEntity($position)
    {
        $data = $this->data[$position];
        if ($data instanceof Entity)
        {
            return $data;
        }
        $entity = $this->entity;
        $this->data[$position] = $data = new $entity($data);
        return $data;
    }

    /**
     * To Array
     * @return array
     */
    public function toArray()
    {
        $data = array();
        foreach ($this->data as $entity)
        {
            if ($entity instanceof Entity)
            {
                $entity = $entity->toArray();
            }
            $data[] = $entity;
        }
        return $data;
    }

    /**
     * Set a data item
     * @param $position int
     * @param $value mixed
     */
    public function offsetSet($position, $value)
    {
        if (is_null($position))
        {
            $this->data[] = $value;
        }
        else
        {
            $this->container[$position] = $value;
        }
    }

    /**
     * Does data item exist?
     * @param $position
     * @return bool
     */
    public function offsetExists($position)
    {
        return isset($this->data[$position]);
    }

    /**
     * Unset a data item
     * @param $position
     */
    public function offsetUnset($position) {
        unset($this->container[$position]);
    }

    /**
     * @param $position
     * @return \ZfcBase\Entity\Entity
     */
    public function offsetGet($position)
    {
        return $this->buildEntity($position);
    }

}
