<?php

namespace Perspective\SystemConfigExample\ViewModel;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Perspective\SystemConfigExample\Helper\Data;
use Magento\Catalog\Block\Product\View as ProductViewBlock;
use Magento\Directory\Model\CurrencyFactory;

class ProductPrice implements ArgumentInterface
{
    /**
     * @var ProductViewBlock
     */
    protected $productViewBlock;

    /** @var Data  */
    protected $helper;

    /** @var ProductRepositoryInterface  */
    protected $productRepository;

    /** @var CurrencyFactory */
    protected $currencyFactory;


    public function __construct(
        ProductViewBlock $productViewBlock,
        Data $helper,
        ProductRepositoryInterface $productRepository,
        CurrencyFactory $currencyFactory,
    ) {
        $this->productViewBlock = $productViewBlock;
        $this->helper = $helper;
        $this->productRepository = $productRepository;
        $this->currencyFactory = $currencyFactory;
    }

    /**
     * Get the current product
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        return $this->productViewBlock->getProduct();
    }

    /**
     * @param $productId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getProductPrice($productId)
    {
        $product = $this->productRepository->getById($productId);
        return $product->getFinalPrice();
    }

    /**
     * @return bool
     */
    public function isUahEnabled()
    {
        return $this->helper->isUahEnabled();
    }

    public function isEuroEnabled()
    {
        return $this->helper->isEuroEnabled();
    }

    public function getUahCourse()
    {
        return $this->helper->getUahCourse();
    }

    public function getEuroCourse()
    {
        return $this->helper->getEuroCourse();
    }

    public function convertToUah($price)
    {
        return $price * $this->getUahCourse();
    }

    public function convertToEuro($price)
    {
        return $price * $this->getEuroCourse();
    }

    public function isSystemCurrenciesEnabled()
    {
        return $this->helper->isSystemCurrenciesEnabled();
    }

    /**
     * Get converted prices for all allowed system currencies.
     *
     * @param float $basePrice
     * @return array
     */
    public function getSystemConvertedPrices($basePrice)
    {
        $prices = [];

        // Check if system currency conversion is enabled
        if (!$this->helper->isSystemCurrenciesEnabled()) {
            return $prices; // Return empty array if conversion is disabled
        }

        $allowedCurrencies = $this->helper->getAllowedCurrencies();
        $baseCurrencyCode = $this->getBaseCurrencyCode();

        /** @var \Magento\Directory\Model\Currency $baseCurrency */
        $baseCurrency = $this->currencyFactory->create()->load($baseCurrencyCode);

        // Get currency rates
        $rates = $baseCurrency->getCurrencyRates($baseCurrencyCode, $allowedCurrencies);

        foreach ($allowedCurrencies as $currencyCode) {
            if ($currencyCode == $baseCurrencyCode) {
                $prices[$currencyCode] = $basePrice;
            } elseif (isset($rates[$currencyCode])) {
                $convertedPrice = $basePrice * $rates[$currencyCode];
                $prices[$currencyCode] = $convertedPrice;
            }
        }

        return $prices;
    }

    /**
     * Get base currency code.
     *
     * @return string
     */
    public function getBaseCurrencyCode()
    {
        return $this->helper->getBaseCurrencyCode();
    }


}
