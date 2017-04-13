<?php


namespace TomWright\CurrencyPHP;


interface ConversionRateFetcherInterface
{

    /**
     * Gets the Currency conversion rate between $from and $to.
     * @param Currency $from
     * @param Currency $to
     * @return float|null
     */
    public function getConversionRate(Currency $from, Currency $to);

}
