<?php

namespace Convertim\VariantRetriever\Retriever;

interface VariantRetrieverInterface
{
    /**
     * @param \Convertim\VariantRetriever\ValueObject\Experiment $experiment
     * @param string $userIdentifier
     * @return \Convertim\VariantRetriever\ValueObject\Variant
     */
    public function getVariantForExperiment($experiment, $userIdentifier);
}
