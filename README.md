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

The following example provides the exchange rates between `GBP` and `USD`.

```php
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

$gbp = new Currency('GBP');
$usd = new Currency('USD');

$priceInGBP = 100;
$priceInUSD = $gbp->convertTo($usd, $priceInGBP);
echo $priceInUSD; // 126
```

I would recommend querying a database at this point to find the most recent conversion rate, but the above is good enough as an example.