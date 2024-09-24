<?php
namespace Perspective\CreateSimpleProduct\Block;
class CreateProd extends \Magento\Framework\View\Element\Template
{

    //protected $_productOptions;
    protected $_productFactory;
    protected $_productRepository;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = [])

    {
        $this->_productFactory = $productFactory;
        $this->_productRepository = $productRepository;
        // $this->_stockRegistry = $stockRegistry;
        parent::__construct($context, $data);
    }

    public function CrPr()
    {
        $product = $this->_productFactory->create();
        $product->setSku('wa-4'); // Set your sku here
        $product->setName('Wali4'); // Name of Product
        $product->setAttributeSetId(4); // Attribute set id
        $product->setStatus(1); // Status on product enabled/ disabled 1/0
        $product->setWeight(10); // weight of product
        $product->setVisibility(4); // visibilty of product (catalog / search / catalog, search / Not visible individually)
        //$product->setTaxClassId(0); // Tax class id
        // $product->setTypeId('simple'); // type of product (simple/virtual/downloadable/configurable)
        $product->setPrice(100); // price of product
        $product->setStockData(
            array(
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
                'qty' => 999999999
            )
        );
        $product->save();
        return($product);
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }

}


?>
