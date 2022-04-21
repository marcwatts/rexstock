<?php
namespace Marcwatts\Rexstock\Model\Config;
 
class Custom implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('SKU (Magento Default)')],
            ['value' => 1, 'label' => __('retail_express_id (Custom)')]
        ];
    }
}
