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
namespace Marcwatts\Rexstock\Helper;

use \SoapClient;
use \SoapHeader;
use \SimpleXMLElement;
 
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $stockRegistry;
    protected $scopeConfig;
    protected $isEnabled;
    protected $resources;
    protected $searchCriteriaBuilder;
    protected $sourceItemRepository;

    
    public function __construct(
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig

    ) {
        $this->stockRegistry = $stockRegistry;
        $this->scopeConfig = $scopeConfig;

    
        $this->storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->isEnabled = $this->scopeConfig->getValue('cistockcheck/general/enable', $this->storeScope);
        $this->isCartCheckEnabled = $this->scopeConfig->getValue('cistockcheck/general/enablecart', $this->storeScope);
        $this->clientId = $this->scopeConfig->getValue('cistockcheck/general/clientid', $this->storeScope);
        $this->username = $this->scopeConfig->getValue('cistockcheck/general/username', $this->storeScope);
        $this->password = $this->scopeConfig->getValue('cistockcheck/general/password', $this->storeScope);
        $this->channelId = $this->scopeConfig->getValue('cistockcheck/general/channelid', $this->storeScope);
        $this->emailalert = $this->scopeConfig->getValue('cistockcheck/general/emailalert', $this->storeScope);
        $this->enablelogging = $this->scopeConfig->getValue('cistockcheck/general/enablelogging', $this->storeScope);
        $this->rexattribute = $this->scopeConfig->getValue('cistockcheck/general/rexid_attribute', $this->storeScope);


    }

    public function getRexId($sku){
        if ($this->rexattribute ==0){
           /// return str_replace("POS-","",$sku);
            return $sku !== null ? str_replace("POS-","",$sku) : $sku;
        }
        else{
            return $sku;
        }
        
    }


    public function removeReservations($sku){
        try {
            $this->resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
            $connection= $this->resources->getConnection();
    
            $logTable = $this->resources->getTableName('cistockcheck_log');
            $sql = "DELETE from inventory_reservation where sku = '".$sku."'";
            $connection->query($sql);
        }
        catch (exception $e) {
            //code to handle the exception
        }
    }

    public function logRequest($sku, $rex_qty, $m2_qty, $channel){
        if ($this->enablelogging == 0){
            return  ;
        }

        try {
            $this->resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
            $connection= $this->resources->getConnection();
    
            $logTable = $this->resources->getTableName('cistockcheck_log');
            $sql = "INSERT INTO " . $logTable . "(sku, rex_qty, m2_qty, channel) VALUES ('".$sku."', '".$rex_qty."','".$m2_qty."','".$channel."')";
            $connection->query($sql);
        }
        catch (exception $e) {
            //code to handle the exception
        }
        

    }


    public function getProductDetails($rexid, $channel ){
        


        $options = [
                'connection_timeout' => 60,
                'exceptions' => false,
                'soap_version' => SOAP_1_2,
                'trace' => true
        ];

        try {
            $client = new SoapClient('https://api.retailexpress.com.au/ecommerce?singleWsdl', $options);
         

            if ($this->clientId && $this->username && $this->password){
                $headerbody = [
                    'ClientID' =>  $this->clientId ,
                    'UserName' =>  $this->username ,
                    'Password' =>  $this->password ,
                ];
            } else {
                return;
            }
            

            $header = new SoapHeader('http://retailexpress.com.au/', 'ClientHeader', $headerbody);


            $action = new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action','http://retailexpress.com.au/IEcommerce/GetProductsByChannel');
            $to = new SoapHeader('http://www.w3.org/2005/08/addressing', 'To', 'https://api.retailexpress.com.au/ecommerce?singleWsdl');
            $client->__setSoapHeaders([$header, $action, $to]);

            $result = $client->GetProductsByChannel([
                'ChannelId' => $channel,
                'ProductIds' => [$rexid]
            ]);

            $obj = simplexml_load_string ($result->GetProductsByChannelResult->any);

            if ( $obj->Fault ){
                if (strlen($this->emailalert) > 0){
                    mail($this->emailalert,'Stock Check Error', "Error on product with id " . $rexid .  PHP_EOL . print_r($obj->Fault, true));
                }
            
                return false;
            } else {
            
                foreach ($obj->Products as $p){
                    foreach($p->Product as $product){
                        if ($product->ProductId == $rexid){
                            return $product;
                            break;
                        }
                    
                    }
                }
                
            }

        } 
        catch (Exception $e) {
            return;
            
        } 
        return;
        
    }

}