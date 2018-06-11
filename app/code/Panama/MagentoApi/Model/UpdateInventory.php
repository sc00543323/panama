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
	
		
		
		$skuDetailsArray = $inventoryData->getSkuDetails();		
		$inventoryDataResponse = array();
		$i = 0;
		foreach($skuDetailsArray as $skuDetails) {
			$sku = $skuDetails->getSku();
			$qty = $skuDetails->getQty();
			if(!$sku) {
				$inventoryDataResponse[$i]["ResultCode"]=0;
				$inventoryDataResponse[$i]["SkuId"]=$sku;
				$inventoryDataResponse[$i]["ResultMessage"]="sku is mandatory";				
			} else if(!isset($qty) || trim($qty)==='') {
				$inventoryDataResponse[$i]["ResultCode"]= 0;
				$inventoryDataResponse[$i]["SkuId"]=$sku;
				$inventoryDataResponse[$i]["ResultMessage"]="qty is mandatory";
			} else {
				//return $inventoryData; die;
				$product=$this->_product->loadByAttribute('sku', $sku); //load product which you want to update stock
				if($product && ($product->getTypeId()=='simple' || $product->getTypeId() == 'virtual')){
				
				$stockItem=$this->_stockRegistry->getStockItem($product->getId()); // load stock of that product
			
				//$stockItem->setData('qty',$qty); //set updated quantity
				$product->setStockData(['qty' => $qty, 'is_in_stock' => $qty > 0]);
				
				$stockItem->setData('use_config_notify_stock_qty',1);
				$stockItem->save(); //save stock of item
				$product->save(); //  also save product
						
				$inventoryDataResponse[$i]["ResultCode"]=1;
				$inventoryDataResponse[$i]["SkuId"]=$sku;
				$inventoryDataResponse[$i]["ResultMessage"]="inventory updated";
				} else {
					$inventoryDataResponse[$i]["ResultCode"]=0;
					$inventoryDataResponse[$i]["SkuId"]=$sku;
					$inventoryDataResponse[$i]["ResultMessage"]="Invalid Sku";	
				}
			}
			$i++;
		}
		return json_encode($inventoryDataResponse);
	
   
    }
}