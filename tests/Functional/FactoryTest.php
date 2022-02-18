<?php

namespace Convertim\VariantRetriever\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Convertim\VariantRetriever\Factory\VariantRetrieverFactory;
use Convertim\VariantRetriever\Retriever\VariantRetriever;
use Convertim\VariantRetriever\Tests\Helpers;
use Convertim\VariantRetriever\ValueObject\Experiment;
use Convertim\VariantRetriever\ValueObject\Variant;

class FactoryTest extends TestCase
{

    public function testFactory()
    {
        $variantRetrieverFactory = new VariantRetrieverFactory();

        $configuration = [
            Helpers::DEFAULT_EXPERIMENT_NAME => [
                ['control' => 70],
                ['variant' => 30]
            ]
        ];

        $variantRetrieverExpected = new VariantRetriever();
        $variantRetrieverExpected->addExperiment(
            new Experiment(
                Helpers::DEFAULT_EXPERIMENT_NAME,
                [
                    new Variant('control', 70),
                    new Variant('variant', 30),
                ]
            )
        );

        $this->assertEquals($variantRetrieverExpected, $variantRetrieverFactory->createVariantRetriever($configuration));
    }
}
