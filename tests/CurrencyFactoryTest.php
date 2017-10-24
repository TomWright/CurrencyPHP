<?php

use PHPUnit\Framework\TestCase;
use TomWright\CurrencyPHP\ConversionRateFetcherInterface;
use TomWright\CurrencyPHP\Currency;
use TomWright\CurrencyPHP\CurrencyFactory;

class CurrencyConversionTestRateFetcherOne implements ConversionRateFetcherInterface
{

    public function getConversionRate(Currency $from, Currency $to)
    {
        return 1;
    }
}

class CurrencyConversionTestRateFetcherTwo implements ConversionRateFetcherInterface
{

    public function getConversionRate(Currency $from, Currency $to)
    {
        return 2;
    }
}

class CurrencyFactoryTest extends TestCase
{

    public function testFactoryPassesCorrectRateFetcher()
    {
        $factory = new CurrencyFactory(new CurrencyConversionTestRateFetcherOne());

        $currencyOne = $factory->create('GBP');
        $currencyTwo = $factory->create('USD');

        $this->assertEquals(1, $currencyOne->convertTo($currencyTwo, 1));

        $factory = new CurrencyFactory(new CurrencyConversionTestRateFetcherTwo());

        $currencyOne = $factory->create('GBP');
        $currencyTwo = $factory->create('USD');

        $this->assertEquals(2, $currencyOne->convertTo($currencyTwo, 1));
    }

}