<?php

namespace Marcwatts\Rexstock\Plugin;

use Magento\Checkout\Model\Cart;
use \SoapClient;
use \SoapHeader;
use \SimpleXMLElement;

class PreventAddToCart
{
    protected $stockRegistry;
    protected $scopeConfig;
    protected $isEnabled;
    protected $resources;
    protected $helper;
    
    public function __construct(
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        
        \Marcwatts\Rexstock\Helper\Data $helper
    ) {
        $this->stockRegistry = $stockRegistry;
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
    
        $this->storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->isEnabled = $this->scopeConfig->getValue('cistockcheck/general/enable', $this->storeScope);
        $this->isCartCheckEnabled = $this->scopeConfig->getValue('cistockcheck/general/enablecart', $this->storeScope);
        $this->clientId = $this->scopeConfig->getValue('cistockcheck/general/clientid', $this->storeScope);
        $this->username = $this->scopeConfig->getValue('cistockcheck/general/username', $this->storeScope);
        $this->password = $this->scopeConfig->getValue('cistockcheck/general/password', $this->storeScope);
        $this->channelId = $this->scopeConfig->getValue('cistockcheck/general/channelid', $this->storeScope);


    }


    
    public function beforeAddProduct(Cart $subject, $productInfo, $requestInfo = null)
    {

 
        if ($this->isEnabled == 0){
            return  ;
        }
        if ($this->isCartCheckEnabled == 0){
            return  ;
        }
        if (!isset($requestInfo) ){
            return;
        }
        if (isset($requestInfo['selected_configurable_option']) && strlen($requestInfo['selected_configurable_option']) > 2 ){
            // configurable
            $productId =  $requestInfo['selected_configurable_option'];

        } else {
            // simple
            if ($productInfo->getId()){
                $productId = $productInfo->getId();

            } else{
                return;
            }
        }


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_product = $objectManager->get('Magento\Catalog\Model\Product')->load($productId );

        if($_product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE){
            // need to skip configurables 
            return;
        }
  
        $sku = $_product->getSku(); 

        // this needs to change to just read the attribute REX id
        $rexId = $this->helper->getRexId($sku);


        $stockItem = $this->stockRegistry->getStockItem($productId, $productInfo->getStore()->getWebsiteId());
        $currentStock = $stockItem->getQty();
        
        $rexProduct = $this->helper->getProductDetails($rexId, 1);


       

        if (isset($rexProduct) && isset($rexProduct->StockAvailable)){
            $this->helper->logRequest($sku, $rexProduct->StockAvailable,  $currentStock,  $this->channelId );
        } 
       
        
        
        
        if ($rexProduct){

            $stockRex = $rexProduct->StockAvailable;
            
            
            // check if stock is different and update
            if ($currentStock != $stockRex){
                $stockItem->setQty($stockRex);
                $this->stockRegistry->updateStockItemBySku($sku, $stockItem);

                $this->helper->removeReservations($sku);
            }
           
        } 
       
       
      //  if (1==1) {
      //     throw new \Magento\Framework\Exception\LocalizedException(__("Error adding to cart". $this->isEnabled . " "   ));
      //  }
        return [$productInfo,$requestInfo];
    }
}