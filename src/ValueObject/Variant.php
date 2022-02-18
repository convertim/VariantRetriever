<?php

namespace Convertim\VariantRetriever\ValueObject;

class Variant
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $rollout;

    /**
     * @param string $name
     * @param int $rollout
     */
    public function __construct($name, $rollout = 50)
    {
        $this->name = $name;
        $this->rollout = $rollout;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getRollout()
    {
        return $this->rollout;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
