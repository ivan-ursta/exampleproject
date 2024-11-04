<?php
namespace Perspective\DbWareHouse\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Post implements ArgumentInterface
{
    /**
     * @var \Perspective\DbWareHouse\Model\PostFactory
     */
    private $_postFactory;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $_productRepository;

    /**
     * @var \Perspective\DbWareHouse\Model\ResourceModel\Post\CollectionFactory
     */
    private $_postCollectionFactory;

    public function __construct(
        \Perspective\DbWareHouse\Model\PostFactory $postFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Perspective\DbWareHouse\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory   
    )
    {
        $this->_postFactory = $postFactory;
        $this->_productRepository = $productRepository;
        $this->_postCollectionFactory = $postCollectionFactory;
        
    }

    public function getPostCollection()
    {
        $post = $this->_postFactory->create();
        $collection = $post->getCollection();

        return $collection;
    }

    public function getProductName($id)
    {
        $productModel = $this->_productRepository->getById($id);
        return $productModel->getName();
    }

    public function getCollectionByWarehouse($warehouseName)
    {
        $collection = $this->_postCollectionFactory
                ->create()
                ->addFieldToSelect("name_war")
                ->addFieldToSelect("id_cat")
                ->addFieldToSelect("id_prod")
                ->addFieldToSelect("data_prod")
                ->addFieldToFilter("name_war", ["eq" => $warehouseName]);
        
        return $collection;
    }

}
