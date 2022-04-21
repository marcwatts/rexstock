<?php

namespace Marcwatts\Rexstock\Model\ResourceModel\Sources;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Marcwatts\Rexstock\Model\Sources;
use Marcwatts\Rexstock\Model\ResourceModel\Sources as ResourceModelSources;

class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Sources::class, ResourceModelSources::class);
    }
}
