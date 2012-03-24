<?php
namespace ZfcFacebook\Graph;

use ZfcFacebook\Graph,
    ZfcFacebook\Graph\Entity;

abstract class Collection implements \Countable, \Iterator
{
    /**
     * @var int
     */
    protected $position = 0;
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
            $this->data = $data;
    }

    public function getIterator() {
        return new ArrayIterator($this);
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->buildEntity($this->position);
    }

    public function next()
    {
        $this->position++;
    }

    public function count()
    {
        return count($this->data);
    }

    /**
     * Build Entity
     * @return Entity;
     */
    protected function buildEntity($position)
    {
        $data = $this->data[$position];
        if ($data instanceof Entity) {
            return $data;
        }
        $entity = $this->entityClass;
        $this->data[$position] = $data = new $entity($data);
        return $data;
    }


}
