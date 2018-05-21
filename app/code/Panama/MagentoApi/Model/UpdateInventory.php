<?php
namespace Panama\MagentoApi\Model;
use Panama\MagentoApi\Api\UpdateInventoryInterface;

class UpdateInventory implements UpdateInventoryInterface
{
 	protected $_product;
 
    /**
     * @var Magento\CatalogInventory\Api\StockStateInterface 
     */
    protected $_stockStateInterface;
 
    /**
     * @var Magento\CatalogInventory\Api\StockRegistryInterface 
     */
    protected $_stockRegistry;
 
    /**    
    * @param Magento\Catalog\Model\Product $product
    * @param Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface,
    * @param Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    */
    public function __construct(
       
        \Magento\Catalog\Model\Product $product,
        \Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {
        $this->_product = $product;
        $this->_stockStateInterface = $stockStateInterface;
        $this->_stockRegistry = $stockRegistry;
        
    }
   /**    
    * @param Magento\Catalog\Model\Product $product
    * @param Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface,
    * @param Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    */
    public function updateInventory(\Panama\MagentoApi\Api\Data\InventoryInterface $inventoryData) {
	
		
		$sku = $inventoryData->getSku();
		$qty = $inventoryData->getQty();
		$inventoryDataResponse = array();
		if(!$sku) {
			$inventoryDataResponse[0]["code"]=0;
			$inventoryDataResponse[0]["Message"]="sku is mandatory";
			$inventoryData->setResultMessage('sku is mandatory');
		} else if(!isset($qty) || trim($qty)==='') {
			$inventoryDataResponse[0]["code"]= 0;
			$inventoryDataResponse[0]["Message"]="qty is mandatory";			
		} else {
			
		
		//return $inventoryData; die;
        $product=$this->_product->loadByAttribute('sku', $sku); //load product which you want to update stock
		if($product){
		
        $stockItem=$this->_stockRegistry->getStockItem($product->getId()); // load stock of that product
    
        $stockItem->setData('qty',$qty); //set updated quantity 
        
        $stockItem->setData('use_config_notify_stock_qty',1);
        $stockItem->save(); //save stock of item
        $product->save(); //  also save product
				
		$inventoryDataResponse[0]["code"]=1;
		$inventoryDataResponse[0]["sku"]=$sku;
		$inventoryDataResponse[0]["Message"]="inventory updated";
		}else{
			$inventoryDataResponse[0]["code"]=0;
			$inventoryDataResponse[0]["Message"]="Invalid Sku";	
		}
		
	}	
	return json_encode($inventoryDataResponse);
	
   
    }
}