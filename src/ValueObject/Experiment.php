<?php

namespace Convertim\VariantRetriever\ValueObject;

use Convertim\VariantRetriever\Exception\LogicalException;

class Experiment
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \Convertim\VariantRetriever\ValueObject\Variant[]
     */
    private $variants;

    /**
     * @param string $name
     * @param \Convertim\VariantRetriever\ValueObject\Variant[] $variants
     */
    public function __construct($name, $variants = [])
    {
        if (!empty($variants)) {
            $variantNames = [];
            $totalPercentage = 0;

            foreach ($variants as $variant) {
                if (isset($variantNames[$variant->getName()])) {
                    throw new LogicalException(sprintf('Variant with same name "%s" already added', $variant->getName()));
                }
                $variantNames[$variant->getName()] = true;
                $totalPercentage += $variant->getRollout();
            }

            if ($totalPercentage !== 100) {
                throw new LogicalException(sprintf('Differents variants do not reach 100%% got %d', $totalPercentage));
            }
        }

        $this->name = $name;
        $this->variants = $variants;
    }

    /**
     * @return \Convertim\VariantRetriever\ValueObject\Variant[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->toString();
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
