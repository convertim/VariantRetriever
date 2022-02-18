<?php

namespace Convertim\VariantRetriever\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Convertim\VariantRetriever\Retriever\VariantRetriever;
use Convertim\VariantRetriever\Tests\Helpers;
use Convertim\VariantRetriever\ValueObject\Experiment;
use Convertim\VariantRetriever\ValueObject\Variant;

class RolloutPercentageTest extends TestCase
{

    public function testIntegerFollowingListShouldHaveCorrectPercentageRollout()
    {

        $variantRetriever = Helpers::generateVariantRetriever();

        $results = [];
        foreach (range(1, 500) as $value) {
            // $randomIdentifier = rand(1, 3000000);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), (string)$value);
        }

        $this->assertCount(500, $results);

        $rollout = array_count_values(Helpers::readRollout($results));

        // 2% diff is allowed for 500 query
        $this->assertGreaterThanOrEqual(240, $rollout['control']); // 48
        $this->assertGreaterThanOrEqual(240, $rollout['variant']); // 48
    }

    public function testSmallListShouldHaveCorrectPercentageRollout()
    {
        $variantRetriever = Helpers::generateVariantRetriever();

        $results = [];
        foreach (range(1, 100) as $value) {
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), (string)$value);
        }

        $this->assertCount(100, $results);

        $rollout = array_count_values(Helpers::readRollout($results));

        $this->assertGreaterThanOrEqual(40, $rollout['control']); // 40
        $this->assertGreaterThanOrEqual(40, $rollout['variant']); // 40
    }

    public function testRandomNumbersShouldHaveCorrectPercentageRollout()
    {
        $variantRetriever = Helpers::generateVariantRetriever();

        $results = [];
        foreach (range(1, 1000) as $value) {
            $randomIdentifier = rand(1, 3000000);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), (string)$randomIdentifier);
        }

        $this->assertCount(1000, $results);

        $rollout = array_count_values(Helpers::readRollout($results));

        // 5% diff is allowed for 1000 query
        $this->assertGreaterThanOrEqual(450, $rollout['control']); // 45
        $this->assertGreaterThanOrEqual(450, $rollout['variant']); // 45
    }

    public function testRandomStringsShouldHaveCorrectPercentageRollout()
    {
        $variantRetriever = Helpers::generateVariantRetriever();

        $results = [];
        foreach (range(1, 200) as $value) {
            $randomIdentifier = rand(1, 3000000);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5($randomIdentifier));
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5(uniqid()));
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), uniqid());
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), uniqid().$value);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), sha1(uniqid()));
        }

        $this->assertCount(1000, $results);

        $rollout = array_count_values(Helpers::readRollout($results));

        // 5% diff is allowed for 1000 query
        $this->assertGreaterThanOrEqual(450, $rollout['control']); // 45
        $this->assertGreaterThanOrEqual(450, $rollout['variant']); // 45
    }

    public function testHugeVolumeShouldHaveVeryCorrectPercentageRollout()
    {
        $variantRetriever = Helpers::generateVariantRetriever();

        $results = [];
        foreach (range(1, 100000) as $value) {
            $randomIdentifier = rand(1, 3000000);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5($randomIdentifier));
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5(uniqid()));
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), uniqid());
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), uniqid().$value);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), sha1(uniqid()));
        }

        $this->assertCount(500000, $results);

        $rollout = array_count_values(Helpers::readRollout($results));

        // 2% diff is allowed for 1000 query
        $this->assertGreaterThanOrEqual(245000, $rollout['control']); // 49.00
        $this->assertGreaterThanOrEqual(245000, $rollout['variant']); // 49.00
    }

    public function testMultiVariantShouldHaveCorrectPercentageRollout()
    {
        $variantRetriever = new VariantRetriever();
        $variantRetriever->addExperiment(new Experiment('my-ab-test', [
            new Variant('control1', 10),
            new Variant('variant2', 10),
            new Variant('variant3', 10),
            new Variant('variant4', 10),
            new Variant('variant5', 10),
            new Variant('variant6', 10),
            new Variant('variant7', 10),
            new Variant('variant8', 10),
            new Variant('variant9', 10),
            new Variant('variant0', 10),
        ]));

        $results = [];
        foreach (range(1, 100000) as $value) {
            $randomIdentifier = rand(1, 3000000);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5($randomIdentifier));
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5(uniqid()));
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), uniqid());
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), uniqid().$value);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), sha1(uniqid()));
        }

        $this->assertCount(500000, $results);

        $rollout = array_count_values(Helpers::readRollout($results));

        $this->assertGreaterThanOrEqual(49000, $rollout['control1']); // 9.80
        $this->assertGreaterThanOrEqual(49000, $rollout['variant2']); // 9.80
    }

    public function testMultiVariantWithDifferentRolloutShouldHaveCorrectPercentageRollout()
    {
        $variantRetriever = new VariantRetriever();
        $variantRetriever->addExperiment(new Experiment('my-ab-test', [
            new Variant('control1', 10),
            new Variant('variant2', 10),
            new Variant('variant3', 80),
        ]));

        $results = [];
        foreach (range(1, 100000) as $value) {
            $randomIdentifier = rand(1, 3000000);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5($randomIdentifier));
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5(uniqid()));
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), uniqid());
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), uniqid().$value);
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), sha1(uniqid()));
        }

        $this->assertCount(500000, $results);

        $rollout = array_count_values(Helpers::readRollout($results));

        $this->assertGreaterThanOrEqual(49400, $rollout['control1']); // 9.88
        $this->assertGreaterThanOrEqual(49400, $rollout['variant2']); // 9.88
        $this->assertGreaterThanOrEqual(399000, $rollout['variant3']); // 79.80
    }

    public function testGenerateRolloutFast()
    {
        $variantRetriever = Helpers::generateVariantRetriever();

        $results = [];

        $start = microtime(true);
        foreach (range(1, 10000) as $value) {
            $results[] = $variantRetriever->getVariantForExperiment(new Experiment('my-ab-test'), md5($value));
        }
        $timeElapsedSecs = microtime(true) - $start;

        $this->assertLessThan(1, $timeElapsedSecs);
    }
}
