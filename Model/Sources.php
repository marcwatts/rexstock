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
namespace Marcwatts\Rexstock\Model;

use Magento\Framework\Model\AbstractModel;
use Marcwatts\Rexstock\Api\Data\SourcesInterface;
use Marcwatts\Rexstock\Model\ResourceModel\Sources as ResourceModelSources;

/**
 * Class Sources
 * @package Marcwatts\Rexstock\Model
 */
class Sources extends AbstractModel implements SourcesInterface
{
    public function _construct()
    {
        $this->_init(ResourceModelSources::class);
    }

    /**
     * @return null|string
     */
    public function getSources(): ?string
    {
        return $this->getData(SourcesInterface::SOURCES_KEY);
    }

    /**
     * @param string $sources
     * @return SourcesInterface
     */
    public function setSources(string $sources): SourcesInterface
    {
        return $this->setData(SourcesInterface::SOURCES_KEY, $sources);
    }

    /**
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->getData(SourcesInterface::ORDER_ID_KEY);
    }

    /**
     * @param string $id
     * @return SourcesInterface
     */
    public function setOrderId(string $id): SourcesInterface
    {
        return $this->setData(SourcesInterface::ORDER_ID_KEY, $id);
    }
}
