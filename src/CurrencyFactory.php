<?php

namespace TomWright\CurrencyPHP;


class CurrencyFactory
{

    /**
     * @var ConversionRateFetcherInterface
     */
    private $rateFetcher;

    /**
     * CurrencyFactory constructor.
     * @param ConversionRateFetcherInterface $rateFetcher
     */
    public function __construct(ConversionRateFetcherInterface $rateFetcher)
    {
        $this->setRateFetcher($rateFetcher);
    }

    /**
     * @return ConversionRateFetcherInterface
     */
    public function getRateFetcher()
    {
        return $this->rateFetcher;
    }

    /**
     * Set which rate fetcher should be given to create Currencies.
     * @param ConversionRateFetcherInterface $rateFetcher
     * @return $this
     */
    public function setRateFetcher($rateFetcher)
    {
        $this->rateFetcher = $rateFetcher;
        return $this;
    }

    /**
     * Instantiate and return a new Currency object.
     * @param string $currencyCode
     * @return Currency
     */
    public function create($currencyCode)
    {
        return new Currency($currencyCode, $this->getRateFetcher());
    }

}