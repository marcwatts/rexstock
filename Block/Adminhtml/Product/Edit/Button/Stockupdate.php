<?php
namespace Marcwatts\Rexstock\Block\Adminhtml\Product\Edit\Button;



class Stockupdate extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic
{

	
    public function getButtonData()
    {
		
		
    	return [
        	'label' => __('Update Stock'),
        	'class' => 'action-secondary',
        	//'on_click' => 'alert("Hello World")',
			'on_click' => 'setLocation("'.$this->getUpdatestockUrl().'")',
        	'sort_order' => 10
    	];
        // https://magento.stackexchange.com/questions/265951/how-to-add-onclick-method-in-magento2-admin-custom-module-page
    }
	
	public function getUpdatestockUrl() 
	{
		$url = $this->context;

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$product = $objectManager->get('Magento\Framework\Registry')->registry('current_product');//get current product

    	return $url->getUrl('updatestock/index/index', array('product' => $product->getId()));
	}
}