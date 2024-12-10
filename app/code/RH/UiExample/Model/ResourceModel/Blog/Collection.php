<?php

namespace RH\UiExample\Model\ResourceModel\Blog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use RH\UiExample\Model\Blog as BlogModel;
use RH\UiExample\Model\ResourceModel\Blog as BlogResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(BlogModel::class, BlogResourceModel::class);
    }
}
