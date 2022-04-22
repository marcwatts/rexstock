<?php
/**
* Marc Watts
* Copyright (c) 2022 Marc Watts <marc@marcwatts.com.au>
*
* @author Marc Watts <marc@marcwatts.com.au>
* @copyright Copyright (c) Marc Watts (https://marcwatts.com.au/)
* @license Proprietary https://marcwatts.com.au/terms-and-conditions.html
* @package Marcwatts_Rexstock
*/
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
