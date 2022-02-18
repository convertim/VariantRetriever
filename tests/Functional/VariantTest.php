<?php

namespace Convertim\VariantRetriever\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Convertim\VariantRetriever\Exception\LogicalException;
use Convertim\VariantRetriever\Retriever\VariantRetriever;
use Convertim\VariantRetriever\Tests\Helpers;
use Convertim\VariantRetriever\ValueObject\Experiment;
use Convertim\VariantRetriever\ValueObject\Variant;

class VariantTest extends TestCase
{
    public function testVariationListThatDontMatchTheHundredPercentShouldThrowException()
    {
        $this->expectException(LogicalException::class);
        (new VariantRetriever())->addExperiment(
            new Experiment(
                Helpers::DEFAULT_EXPERIMENT_NAME,
                [
                    new Variant('control'),
                    new Variant('variant', 30)
                ]
            )
        );
    }

    public function testVariantRetrieverRetrieveControlInThisCase()
    {
        $variantRetriever = Helpers::generateVariantRetriever();

        $this->assertEquals('control', (string)$variantRetriever->getVariantForExperiment(new Experiment(Helpers::DEFAULT_EXPERIMENT_NAME), '2'));
    }

    public function testVariantRetrieverRetrieveVariantInThisCase()
    {
        $variantRetriever = Helpers::generateVariantRetriever();

        $this->assertEquals('variant', (string)$variantRetriever->getVariantForExperiment(new Experiment(Helpers::DEFAULT_EXPERIMENT_NAME), '1'));
    }


    public function testAnyVariantRetrieverInstanceAlwaysReturnTheSameVariantForIdentifier()
    {
        $identifier = '17d8a1d5-97ba-42db-a4a7-3b9562f0ff22';
        $variantRetriever = Helpers::generateVariantRetriever();

        $this->assertEquals('control', (string)$variantRetriever->getVariantForExperiment(new Experiment(Helpers::DEFAULT_EXPERIMENT_NAME), $identifier));
        $this->assertEquals('control', (string)$variantRetriever->getVariantForExperiment(new Experiment(Helpers::DEFAULT_EXPERIMENT_NAME), $identifier));

        $variantRetriever = Helpers::generateVariantRetriever();
        $this->assertEquals('control', (string)$variantRetriever->getVariantForExperiment(new Experiment(Helpers::DEFAULT_EXPERIMENT_NAME), $identifier));
    }


    public function testIdentifierCanHaveDifferentVariantOnDifferentExperiment()
    {
        $identifier = '17d8a1d5-97ba-42db-a4a7-3b9562f0ff22';
        $variantRetriever = Helpers::generateVariantRetriever();

        $this->assertEquals('control', (string)$variantRetriever->getVariantForExperiment(new Experiment(Helpers::DEFAULT_EXPERIMENT_NAME), $identifier));

        $variantRetriever = Helpers::generateVariantRetriever('my-other-ab-test');
        $this->assertEquals('variant', (string)$variantRetriever->getVariantForExperiment(new Experiment('my-other-ab-test'), $identifier));
    }

    public function testDifferentsVariantsWithSameNameShouldThrowException()
    {
        $this->expectExceptionMessage('Variant with same name "control" already added');
        (new VariantRetriever())->addExperiment(
            new Experiment(Helpers::DEFAULT_EXPERIMENT_NAME, [
                new Variant('control'),
                new Variant('variant1', 20),
                new Variant('control', 20),
                new Variant('variant1', 10),
            ])
        );

        // throws(LogicalException::class)
    }
}
