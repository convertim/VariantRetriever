<?php

namespace Convertim\VariantRetriever\Retriever;

use Convertim\VariantRetriever\Exception\LogicalException;
use Convertim\VariantRetriever\ValueObject\Experiment;

class VariantRetriever implements VariantRetrieverInterface
{
    /**
     * @var array
     */
    private $experiments;

    /**
     * @var array
     */
    private $allocations = [];

    /**
     * @param \Convertim\VariantRetriever\ValueObject\Experiment $experiment
     * @return \Convertim\VariantRetriever\Retriever\VariantRetriever
     */
    public function addExperiment(Experiment $experiment)
    {
        $this->experiments[$experiment->getName()] = $experiment;

        return $this;
    }

    /**
     * @param \Convertim\VariantRetriever\ValueObject\Experiment $experiment
     * @param string $userIdentifier
     * @return \Convertim\VariantRetriever\ValueObject\Variant
     */
    public function getVariantForExperiment($experiment, $userIdentifier)
    {
        if (!isset($this->experiments[$experiment->getName()])) {
            throw new LogicalException(sprintf('Experiment %s do not exist', $experiment->getName()));
        }

        $this->createVariantAllocation($this->experiments[$experiment->getName()]);

        return $this->allocations[$experiment->getName()][$this->getUserIdentifierAffectation($experiment->getName(), $userIdentifier)];
    }

    /**
     * @param \Convertim\VariantRetriever\ValueObject\Experiment$experiment
     */
    private function createVariantAllocation($experiment)
    {
        $this->allocations[$experiment->getName()] = [];
        $variants = $experiment->getVariants();
        foreach ($variants as $variant) {
            $this->allocations[$experiment->getName()] = array_merge($this->allocations[$experiment->getName()], array_fill(0, $variant->getRollout(), $variant));
        }
    }

    /**
     * @param string $experimentName
     * @param string $userIdentifier
     * @return int
     */
    private function getUserIdentifierAffectation($experimentName, $userIdentifier)
    {
        return (int)substr((string)crc32((string)$experimentName . $userIdentifier), -2, 2);
    }
}
