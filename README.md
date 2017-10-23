# CurrencyPHP

[![Build Status](https://travis-ci.org/TomWright/CurrencyPHP.svg?branch=master)](https://travis-ci.org/TomWright/CurrencyPHP)
[![Latest Stable Version](https://poser.pugx.org/tomwright/currency-php/v/stable)](https://packagist.org/packages/tomwright/currency-php)
[![Total Downloads](https://poser.pugx.org/tomwright/currency-php/downloads)](https://packagist.org/packages/tomwright/currency-php)
[![Monthly Downloads](https://poser.pugx.org/tomwright/currency-php/d/monthly)](https://packagist.org/packages/tomwright/currency-php)
[![Daily Downloads](https://poser.pugx.org/tomwright/currency-php/d/daily)](https://packagist.org/packages/tomwright/currency-php)
[![License](https://poser.pugx.org/tomwright/currency-php/license.svg)](https://packagist.org/packages/tomwright/currency-php)

## Installation

```
composer install tomwright/currency-php
```

## Usage

CurrencyPHP is just a basic wrapper. It cannot do conversions out of the box... Saying that... you only need to provide it with the conversion rates.

```php

$rateFetcher = new MyConversionRateFetcher();

$gbp = new Currency('GBP', $rateFetcher);
$usd = new Currency('USD', $rateFetcher);

$priceInGBP = 100;
$priceInUSD = $gbp->convertTo($usd, $priceInGBP);
echo $priceInUSD; // 126
```

## Rate Fetchers

Rate Fetchers are what `CurrencyPHP` uses to get conversion rates. Any Rate Fetcher you create should implement `ConversionRateFetcherInterface`.

### Existing Rate Fetchers

- [Fixer IO](https://github.com/TomWright/CurrencyPHPFixerIORateFetcher)
- [Yahoo Currency API](https://github.com/TomWright/CurrencyPHPYahooRateFetcher)

If you have created your own Rate Fetcher and want it included here, please submit a pull request.

### Creating Your Own

The following Rate Fetcher gives you some fixed exchange rates:
- GBP to USD
- USD to GBP
- GBP to CAD
- CAD to USD

```php
class FixedRateFetcher implements ConversionRateFetcherInterface
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
```

### Handling unknown conversion rates

#### One way conversion rates

The above Rate Fetcher has rates for both GBP to USD, and USD to GBP and this works great... but you'll also notice that it has CAD to USD, but no USD to CAD conversion rates. There is some logic implemented so that you only need to store 1 way conversion rates and it will automatically invert the rate if required.

Thanks to this logic, you can run a USD to CAD conversion using the above Rate Fetcher with no problems. The full list of conversion that the above can handle is as follows:
- GBP to USD
- USD to GBP
- GBP to CAD
- CAD to GBP
- CAD to USD
- USD to CAD

#### Missing conversion rates

If no conversion rate exists at all between the 2 currencies, an `UnhandledConversionRate` Exception will be thrown. 
