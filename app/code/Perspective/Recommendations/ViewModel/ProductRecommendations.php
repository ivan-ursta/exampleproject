<?php

namespace Perspective\Recommendations\ViewModel;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Request\Http;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;


class ProductRecommendations implements ArgumentInterface
{
    private $productRepository;
    private $request;
    private $productCollectionFactory;
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Http $request,
        CollectionFactory $productCollectionFactory
    ) {
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function getRecommendedProducts()
    {
        // get current product id
        $productId = $this->request->getParam('id');
        $currentProduct = $this->productRepository->getById($productId);

        // get categories of the product
        $categoryIds = $currentProduct->getCategoryIds();

        // get price
        $currentPrice = $currentProduct->getPrice();
        $priceMin = $currentPrice - 15;
        $priceMax = $currentPrice + 15;

        // get products that meet the requirements
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(['sku', 'name', 'price', 'image']);
        $collection->addCategoriesFilter(['in' => $categoryIds]);
        $collection->addAttributeToFilter('price', ['gteq' => $priceMin, 'lteq' => $priceMax]);
        $collection->addFieldToFilter('entity_id', ['neq' => $productId]);     // exclude the current product
        $collection->setPageSize(3);

        return $collection;
    }
}
