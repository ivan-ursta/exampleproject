<?php

namespace Perspective\Holidays\Model\Holiday;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Perspective\Holidays\Model\ResourceModel\Holiday\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string            $name
     * @param string            $primaryFieldName
     * @param string            $requestFieldName
     * @param CollectionFactory $holidayCollectionFactory
     * @param array             $meta
     * @param array             $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $holidayCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $holidayCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        /** @var \Perspective\Holidays\Model\Holiday $holiday */
        foreach ($items as $holiday) {
            $data = $holiday->getData();

            // Convert allowed_customer_group to array for multiselect
            if (!empty($data['allowed_customer_group'])) {
                $data['allowed_customer_group'] = explode(',', $data['allowed_customer_group']);
            }

            $this->loadedData[$holiday->getId()] = $data;
        }

        return $this->loadedData;
    }
}
