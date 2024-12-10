<?php

namespace Perspective\Holidays\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class HolidayActions extends Column
{
    /** Url path */
    const URL_PATH_EDIT = 'holidays/index/edit';
    const URL_PATH_DELETE = 'holidays/index/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * Constructor
     */
    public function __construct(
        UrlInterface $urlBuilder,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data       = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['holiday_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href'  => $this->urlBuilder->getUrl(self::URL_PATH_EDIT, ['holiday_id' => $item['holiday_id']]),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href'    => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['holiday_id' => $item['holiday_id']]),
                            'label'   => __('Delete'),
                            'confirm' => [
                                'title'   => __('Delete "${ $.$data.name }"'),
                                'message' => __('Are you sure you want to delete a "${ $.$data.name }" record?')
                            ]
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
