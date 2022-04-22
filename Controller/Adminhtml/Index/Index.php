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
namespace Marcwatts\Rexstock\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{
    
    protected $resultPageFactory;
    protected $resultFactory;
    protected $request;
    protected $helper;

    public function __construct(
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Request\Http $request,
        \Marcwatts\Rexstock\Helper\Data $helper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\ResultFactory $resultFactory
    ) {
        $this->request = $request;
        $this->helper = $helper;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultFactory = $resultFactory;
       
        $this->stockRegistry = $stockRegistry;
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
    
        $this->storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->isEnabled = $this->scopeConfig->getValue('cistockcheck/general/enable', $this->storeScope);
        $this->clientId = $this->scopeConfig->getValue('cistockcheck/general/clientid', $this->storeScope);
        $this->username = $this->scopeConfig->getValue('cistockcheck/general/username', $this->storeScope);
        $this->password = $this->scopeConfig->getValue('cistockcheck/general/password', $this->storeScope);
        $this->channelId = $this->scopeConfig->getValue('cistockcheck/general/channelid', $this->storeScope);

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT); 

        $productId = $this->request->getParam('product');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_product = $objectManager->get('Magento\Catalog\Model\Product')->load($productId );

        

        if($_product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE){
            // need to skip configurables 
            return;
        }
  
        $sku = $_product->getSku(); 

        // this needs to change to just read the attribute REX id
        $rexId = $this->helper->getRexId($sku);
       // mail('bjornishere@gmail.com','Golf Debug', "rexid  " . print_r($requestInfo, true) );

        $stockItem = $this->stockRegistry->getStockItem($productId, 1);
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

        
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
    

    





    protected function _isAllowed()
    {
        return true;
    }
}