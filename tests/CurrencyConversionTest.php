<?php

use PHPUnit\Framework\TestCase;

class Currency extends \TomWright\CurrencyPHP\Currency
{

    /**
     * @param \TomWright\CurrencyPHP\Currency $currency
     * @return float
     */
    public function getConversionRate(\TomWright\CurrencyPHP\Currency $currency)
    {
        $result = 0;
        if ($this->getCurrencyCode() == $currency->getCurrencyCode()) {
            $result = 1;
        } elseif ($this->getCurrencyCode() == 'GBP' && $currency->getCurrencyCode() == 'USD') {
            $result = 1.26;
        } elseif ($this->getCurrencyCode() == 'USD' && $currency->getCurrencyCode() == 'GBP') {
            $result = 0.80;
        }
        return $result;
    }
}

class CurrencyConversionTest extends TestCase
{

    public function testCurrencyConversion()
    {
        $gbp = new Currency('GBP');
        $usd = new Currency('USD');
        $cad = new Currency('CAD');

        $this->assertEquals(100 * 1.26, $gbp->convertTo($usd, 100));
        $this->assertEquals(100 * 0.80, $usd->convertTo($gbp, 100));
        $this->assertEquals(0, $usd->convertTo($cad, 100));
        $this->assertEquals(0, $gbp->convertTo($cad, 100));
        $this->assertEquals(100, $gbp->convertTo($gbp, 100));
        $this->assertEquals(100, $usd->convertTo($usd, 100));
    }

}