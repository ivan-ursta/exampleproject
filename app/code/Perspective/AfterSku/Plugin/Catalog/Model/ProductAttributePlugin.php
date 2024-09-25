<?php

namespace Perspective\AfterSku\Plugin\Catalog\Model;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Framework\App\RequestInterface;

class ProductAttributePlugin
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * After plugin for productAttribute method
     *
     * @param OutputHelper $subject
     * @param string $result
     * @param Product $product
     * @param string $attributeHtml
     * @param string $attributeName
     * @return string
     */
    public function afterProductAttribute(
        OutputHelper $subject,
                     $result,
                     $product,
                     $attributeHtml,
                     $attributeName
    ) {
            if ($attributeName == 'sku') {

                $customSku = 'ID: ' . $product->getId() . ' - ' . $attributeHtml ;
                return $customSku;
            }

        return $result;
    }
}
