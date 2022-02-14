<?php

namespace Travaux\VariantRetriever\Tests;

use Travaux\VariantRetriever\Retriever\VariantRetriever;
use Travaux\VariantRetriever\ValueObject\Experiment;
use Travaux\VariantRetriever\ValueObject\Variant;

class Helpers
{

    const DEFAULT_EXPERIMENT_NAME = 'my-ab-test';

    public static function generateVariantRetriever($name = self::DEFAULT_EXPERIMENT_NAME)
    {
        $variantRetriever = new VariantRetriever();
        return $variantRetriever->addExperiment(new Experiment($name, [new Variant('control'), new Variant('variant')]));
    }

    public static function readRollout(array $results)
    {
        return array_map(function ($d) {
            return (string)$d;
        }, $results);
    }
}
