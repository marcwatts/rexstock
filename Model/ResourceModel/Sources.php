<?php

namespace Marcwatts\Rexstock\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Sources
 * @package Marcwatts\Rexstock\Model\ResourceModel
 */
class Sources extends AbstractDb
{
    public function _construct()
    {
        $this->_init('order_sources', 'extension_id');
    }
}
