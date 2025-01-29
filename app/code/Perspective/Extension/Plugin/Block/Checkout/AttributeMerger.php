<?php

namespace Perspective\Extension\Plugin\Block\Checkout;

class AttributeMerger
{
    public function afterMerge(\Magento\Checkout\Block\Checkout\AttributeMerger $subject,$result)
    {
        $result['firstname']['placeholder'] = __('First Name');
        $result['lastname']['placeholder'] = __('Last Name');
        $result['street']['children'][0]['placeholder'] = __('Line no 1');
        $result['street']['children'][1]['placeholder'] = __('Line no 2');
        $result['city']['placeholder'] = __('Enter City');
        $result['postcode']['placeholder'] = __('Enter Zip/Postal Code');
        $result['telephone']['placeholder'] = __('Enter Phone Number');
        return $result;
    }
}
