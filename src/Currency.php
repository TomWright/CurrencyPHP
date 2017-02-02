<?php


namespace TomWright\CurrencyPHP;


abstract class Currency
{

    /**
     * @var string
     */
    protected $currencyCode;


    /**
     * Currency constructor.
     * @param string $currencyCode
     */
    public function __construct($currencyCode)
    {
        $this->setCurrencyCode($currencyCode);
    }


    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }


    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }


    /**
     * @param Currency $currency
     * @return float
     */
    public abstract function getConversionRate(Currency $currency);


    /**
     * @param Currency $currency
     * @param float $amount
     * @return float
     */
    public function convertTo(Currency $currency, $amount)
    {
        $conversionRate = $this->getConversionRate($currency);
        return ($amount * $conversionRate);
    }


    /**
     * @param Currency $currency
     * @return float
     */
    public function getReverseConversionRate(Currency $currency)
    {
        $rate = $this->getConversionRate($currency);
        $toConversion = 1 * $rate;
        $reverseRate = 1 / $toConversion;
        return $reverseRate;
    }

}