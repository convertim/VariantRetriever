<?php

namespace Travaux\VariantRetriever\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Travaux\VariantRetriever\Factory\VariantRetrieverFactory;
use Travaux\VariantRetriever\Retriever\VariantRetriever;
use Travaux\VariantRetriever\Tests\Helpers;
use Travaux\VariantRetriever\ValueObject\Experiment;
use Travaux\VariantRetriever\ValueObject\Variant;

class FactoryTest extends TestCase
{

    public function testFactory()
    {
        $variantRetrieverFactory = new VariantRetrieverFactory();

        $configuration = [
            Helpers::DEFAULT_EXPERIMENT_NAME => [
                'control' => 50,
                'variant' => 50
            ]
        ];

        $variantRetrieverExpected = new VariantRetriever();
        $variantRetrieverExpected->addExperiment(
            new Experiment(
                Helpers::DEFAULT_EXPERIMENT_NAME,
                [
                    new Variant('control'),
                    new Variant('variant'),
                ]
            )
        );

        $this->assertEquals($variantRetrieverExpected, $variantRetrieverFactory->createVariantRetriever($configuration));
    }
}
