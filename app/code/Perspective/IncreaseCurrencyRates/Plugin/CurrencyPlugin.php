<?php

namespace Perspective\IncreaseCurrencyRates\Plugin;

class CurrencyPlugin
{
    /**
     * After plugin for getCurrencyRates method
     *
     * @param \Magento\Directory\Model\Currency $subject
     * @param array $result
     * @param string $currency
     * @param array|null $toCurrencies
     * @return array
     */
    public function afterGetCurrencyRates(
        \Magento\Directory\Model\Currency $subject,
                                          $result,
                                          $currency,
                                          $toCurrencies = null
    ) {

        foreach ($result as $currencyCode => &$rate) {
            if (is_numeric($rate)) {
                $rate = $rate * 1.10; // Increase rate by 10%
            }
        }
        return $result;
    }
}
