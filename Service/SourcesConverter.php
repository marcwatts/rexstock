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
namespace Marcwatts\Rexstock\Service;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\InventorySourceSelectionApi\Api\Data\SourceSelectionItemInterfaceFactory;

/**
 * Class SourcesConverter
 * @package Marcwatts\Rexstock\Service
 */
class SourcesConverter
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var SourceSelectionItemInterfaceFactory
     */
    private $sourceSelectionItemInterfaceFactory;

    /**
     * SourcesConverter constructor.
     * @param SerializerInterface $serializer
     * @param SourceSelectionItemInterfaceFactory $sourceSelectionItemInterfaceFactory
     */
    public function __construct(
        SerializerInterface $serializer,
        SourceSelectionItemInterfaceFactory $sourceSelectionItemInterfaceFactory
    ) {
        $this->serializer = $serializer;
        $this->sourceSelectionItemInterfaceFactory = $sourceSelectionItemInterfaceFactory;
    }

    /**
     * @param array $sourcesItems
     * @return string
     */
    public function convertSourceSelectionItemsToJson(array $sourcesItems): string
    {
        $sources = [];
        foreach ($sourcesItems as $item) {
            $sources[] = [
                'source_code' => $item->getSourceCode(),
                'sku' => $item->getSku(),
                'qty_to_deduct' => $item->getQtyToDeduct(),
                'qty_available' => $item->getQtyAvailable()
            ];
        }

        return $this->serializer->serialize($sources);
    }

    /**
     * @param string $sources
     * @return array
     */
    public function convertSourcesJsonToSourceSelectionItems(string $sources): array
    {
        $sourcesArray = $this->serializer->unserialize($sources);
        $sourceSelectionItems = [];

        foreach ($sourcesArray as $item) {
            $sourceSelectionItem = $this->sourceSelectionItemInterfaceFactory->create(
                [
                    'sourceCode' => $item['source_code'],
                    'sku' => $item['sku'],
                    'qtyToDeduct' => $item['qty_to_deduct'],
                    'qtyAvailable' => $item['qty_available']
                ]
            );

            $sourceSelectionItems[$item['sku']] = $sourceSelectionItem;
        }

        return $sourceSelectionItems;
    }
}
