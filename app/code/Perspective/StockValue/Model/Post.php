<?php

namespace Perspective\StockValue\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;

class Post extends \Perspective\DbWareHouse\Model\Post
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
    )
    {
        parent::__construct($context, $registry, $productRepository, $resource, $resourceCollection);
    }

    public function getQuantity()
    {
        return $this->getData('kol_prod');
    }

    public function getPrice()
    {
        return $this->productRepository->getById($this->getData('id_prod'))->getPrice();
    }

    public function getProdsPrice()
    {
        return $this->getQuantity() * $this->getPrice();
    }
}
