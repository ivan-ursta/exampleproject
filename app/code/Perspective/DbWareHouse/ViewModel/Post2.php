<?php
namespace Perspective\DbWareHouse\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Post2 implements ArgumentInterface
{
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $_productRepository;

    /**
     * @var \Perspective\DbWareHouse\Model\ResourceModel\Post\CollectionFactory
     */
    private $_postCollectionFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    private $_productImageHelper;

    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Perspective\DbWareHouse\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Magento\Catalog\Helper\Image $productImageHelper
    )
    {
        $this->_productRepository = $productRepository;
        $this->_postCollectionFactory = $postCollectionFactory;
        $this->_productImageHelper = $productImageHelper;
        
    }

    public function getProductImageUrl($productId, $imageId, $attributes = [])
    {
        if(is_null($productId))
        {
            return null;
        }
        $product = $this->_productRepository->getById($productId);
        $imageUrl = $this->_productImageHelper->init($product, $imageId, $attributes)->getUrl();
        return $imageUrl;
    }

    public function getProductByCategoryList($categories)
    {
        $collection = $this->_postCollectionFactory
                ->create()
                ->addFieldToSelect("*")
                ->addFieldToFilter("kol_prod", ["gt" => 0])
                ->addFieldToFilter("id_cat", ["in" => $categories]);

        return $collection;
    }

    public function getProduct($id)
    {
        $productModel = $this->_productRepository->getById($id);
        return $productModel;
    }
}