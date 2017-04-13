<?php

use PHPUnit\Framework\TestCase;
use TomWright\CurrencyPHP\ConversionRateFetcherInterface;
use TomWright\CurrencyPHP\Currency;
use TomWright\CurrencyPHP\Exception\UnhandledConversionRate;

class CurrencyConversionTestRateFetcher implements ConversionRateFetcherInterface
{

    /**
     * @param Currency $from
     * @param Currency $to
     * @return float
     */
    public function getConversionRate(Currency $from, Currency $to)
    {
        $rates = [
            [
                'from' => 'GBP',
                'to' => 'USD',
                'rate' => 1.2547,
            ],
            [
                'from' => 'USD',
                'to' => 'GBP',
                'rate' => 0.7974,
            ],
            [
                'from' => 'GBP',
                'to' => 'CAD',
                'rate' => 1.6612,
            ],
            [
                'from' => 'CAD',
                'to' => 'USD',
                'rate' => 0.7539,
            ],
        ];

        $result = null;

        foreach ($rates as $rate) {
            if ($rate['from'] === $from->getCurrencyCode() && $rate['to'] === $to->getCurrencyCode()) {
                $result = $rate['rate'];
            }
        }
        return $result;
    }
}

class CurrencyConversionTest extends TestCase
{

    public function testCurrencyConversion()
    {
        $rateFetcher = new CurrencyConversionTestRateFetcher();

        $gbp = new Currency('GBP', $rateFetcher);
        $usd = new Currency('USD', $rateFetcher);

        $this->assertEquals(125.47, round($gbp->convertTo($usd, 100), 2));
        $this->assertEquals(79.74, round($usd->convertTo($gbp, 100), 2));
    }


    public function testCurrencyConversionWithOneWayRates()
    {
        $rateFetcher = new CurrencyConversionTestRateFetcher();

        $gbp = new Currency('GBP', $rateFetcher);
        $usd = new Currency('USD', $rateFetcher);
        $cad = new Currency('CAD', $rateFetcher);

        $this->assertEquals(132.64, round($usd->convertTo($cad, 100), 2));
        $this->assertEquals(75.39, round($cad->convertTo($usd, 100), 2));

        $this->assertEquals(166.12, round($gbp->convertTo($cad, 100), 2));
        $this->assertEquals(60.20, round($cad->convertTo($gbp, 100), 2));
    }


    public function testExceptionIsThrownWhenMissingRates()
    {
        $rateFetcher = new CurrencyConversionTestRateFetcher();

        $cad = new Currency('CAD', $rateFetcher);
        $hkd = new Currency('HKD', $rateFetcher);

        $exceptionThrown = false;
        try {
            $this->assertEquals(60.20, round($cad->convertTo($hkd, 100), 2));
        } catch (UnhandledConversionRate $e) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

}