<?php

namespace Travaux\VariantRetriever\Retriever;

interface VariantRetrieverInterface
{
    /**
     * @param \Travaux\VariantRetriever\ValueObject\Experiment $experiment
     * @param string $userIdentifier
     * @return \Travaux\VariantRetriever\ValueObject\Variant
     */
    public function getVariantForExperiment($experiment, $userIdentifier);
}
