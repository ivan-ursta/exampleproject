<?php

namespace RH\UiExample\Model;

use Magento\Ui\DataProvider\AbstractDataProvider;
use RH\UiExample\Model\ResourceModel\Blog\CollectionFactory;


class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    // @codingStandardsIgnoreStart

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blogCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $blogCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    // @codingStandardsIgnoreEnd

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $blog) {
            $this->loadedData[$blog->getBlogId()] = $blog->getData();
        }
        return $this->loadedData;
    }
}
