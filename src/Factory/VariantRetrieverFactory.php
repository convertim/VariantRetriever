<?php

namespace Convertim\VariantRetriever\Factory;

use Convertim\VariantRetriever\Retriever\VariantRetriever;
use Convertim\VariantRetriever\ValueObject\Experiment;
use Convertim\VariantRetriever\ValueObject\Variant;

final class VariantRetrieverFactory
{

    /**
     * @param array $experiments
     * @return \Convertim\VariantRetriever\Retriever\VariantRetriever
     */
    public function createVariantRetriever($experiments)
    {
        $variantRetriever = new VariantRetriever();
        foreach ($experiments as $experimentName => $variants) {
            $experimentVariants = [];

            foreach (array_reduce($variants, 'array_merge', []) as $variantName => $variantRollout) {
                $experimentVariants[] = new Variant($variantName, $variantRollout);
            }

            $variantRetriever->addExperiment(new Experiment($experimentName, $experimentVariants));
        }
        return $variantRetriever;
    }
}
