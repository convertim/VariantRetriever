<?php

namespace Travaux\VariantRetriever\Retriever;

use Travaux\VariantRetriever\Exception\LogicalException;
use Travaux\VariantRetriever\ValueObject\Experiment;
use Travaux\VariantRetriever\ValueObject\Variant;

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
     * @param \Travaux\VariantRetriever\ValueObject\Experiment $experiment
     * @return \Travaux\VariantRetriever\Retriever\VariantRetriever
     */
    public function addExperiment(Experiment $experiment)
    {
        $this->experiments[$experiment->getName()] = $experiment;

        return $this;
    }

    /**
     * @param \Travaux\VariantRetriever\ValueObject\Experiment $experiment
     * @param string $userIdentifier
     * @return \Travaux\VariantRetriever\ValueObject\Variant
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
     * @param \Travaux\VariantRetriever\ValueObject\Experiment$experiment
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
