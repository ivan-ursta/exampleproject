<?php

namespace Perspective\DemoPlugin\Plugins\Catalog\Model;

class Product
{
//    public function afterGetName(\Magento\Catalog\Model\Product $product, $name) // ($product($subject), $name($result))
//    {
//        $price = $product->getData('price');
//
//        if ($price < 60) {
//            $name .= " (So cheap)";
//        }
//        else {
//            $name .= " (So expensive)";
//        }
//
//        return $name;
//    }

    public function beforeSetName(\Magento\Catalog\Model\Product $product, $name)
    {
        return ['(' . $name . ')'];
    }
}
