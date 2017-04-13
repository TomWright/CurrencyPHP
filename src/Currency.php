<?php


namespace TomWright\CurrencyPHP;


use TomWright\CurrencyPHP\Exception\UnhandledConversionRate;

class Currency
{

    /**
     * @var string
     */
    protected $currencyCode;

    /**
     * @var ConversionRateFetcherInterface
     */
    protected $rateFetcher;


    /**
     * Currency constructor.
     * @param string $currencyCode
     * @param ConversionRateFetcherInterface $rateFetcher
     */
    public function __construct($currencyCode, ConversionRateFetcherInterface $rateFetcher)
    {
        $this->setCurrencyCode($currencyCode);
        $this->rateFetcher = $rateFetcher;
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
    public function getConversionRate(Currency $currency)
    {
        return $this->rateFetcher->getConversionRate($this, $currency);
    }


    /**
     * @param Currency $currency
     * @param float $amount
     * @return float
     * @throws UnhandledConversionRate
     */
    public function convertTo(Currency $currency, $amount)
    {
        $conversionRate = $this->getConversionRate($currency);
        if ($conversionRate === null) {
            $conversionRate = $currency->getReverseConversionRate($this);
        }
        if ($conversionRate === null) {
            throw new UnhandledConversionRate("No conversion rate found between {$this->getCurrencyCode()} and {$currency->getCurrencyCode()}.");
        }
        $result = ($amount * $conversionRate);
        return $result;
    }


    /**
     * Inverts the specified conversion rate.
     * @param float $rate
     * @return float
     */
    public static function getFlippedConversionRate($rate)
    {
        $toConversion = 1 * $rate;
        $reverseRate = 1 / $toConversion;
        return $reverseRate;
    }


    /**
     * Returns the conversion rate from $currency to $this.
     * @param Currency $currency
     * @return float
     */
    public function getReverseConversionRate(Currency $currency)
    {
        $rate = $this->getConversionRate($currency);
        if ($rate === null) {
            return $rate;
        }
        return static::getFlippedConversionRate($rate);
    }

}
