<?php
 
namespace Panama\MagentoApi\Model;

use Panama\MagentoApi\Api\Data\InventoryInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
/**
 * Defines a data structure representing a point, to demonstrating passing
 * more complex types in and out of a function call.
 */
class Inventory extends AbstractExtensibleModel implements InventoryInterface
{
     /**     
	 * @return array
     */
    public function getSkuDetails()
    {
        return $this->getData(self::skuDetails);		
    }

    /**
     * @param array $skuDetails
     * @return $this
     */
    public function setSkuDetails($skuDetails)
    {
        return $this->setData(self::skuDetails, $skuDetails);
    }

}