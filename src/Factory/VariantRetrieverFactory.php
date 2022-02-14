<?php

namespace Travaux\VariantRetriever\Factory;

use Travaux\VariantRetriever\Retriever\VariantRetriever;
use Travaux\VariantRetriever\Retriever\VariantRetrieverInterface;
use Travaux\VariantRetriever\ValueObject\Experiment;
use Travaux\VariantRetriever\ValueObject\Variant;

final class VariantRetrieverFactory
{

    /**
     * @param array $experiments
     * @return \Travaux\VariantRetriever\Retriever\VariantRetriever
     */
    public function createVariantRetriever($experiments)
    {
        $variantRetriever = new VariantRetriever();
        foreach ($experiments as $experimentName => $variants) {
            $experimentVariants = [];

            foreach ($variants as $variantName => $variantRollout) {
                $experimentVariants[] = new Variant($variantName, $variantRollout);
            }

            $variantRetriever->addExperiment(new Experiment($experimentName, $experimentVariants));
        }
        return $variantRetriever;
    }
}
