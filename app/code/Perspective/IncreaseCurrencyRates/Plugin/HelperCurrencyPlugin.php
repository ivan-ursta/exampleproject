<?php

namespace Perspective\IncreaseCurrencyRates\Plugin;

class HelperCurrencyPlugin
{
    public function afterGetUahCourse(
        \Perspective\SystemConfigExample\Helper\Data $subject, $result
    ) {
        if (is_numeric($result)) {
            $result *= 1.1;
        }
        return $result;
    }

    public function afterGetEuroCourse(
        \Perspective\SystemConfigExample\Helper\Data $subject, $result
    ) {
        if (is_numeric($result)) {
            $result *= 1.1;
        }
        return $result;
    }
}
