<?php
namespace Panama\Handset\Plugin;
 
class Product
{
	public function __construct(
        \Panama\Handset\Model\HandsetFactory $handsetFactory,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->handsetFactory = $handsetFactory;
        $this->catalogSession = $catalogSession;
    }

    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        $now = new \DateTime();
    	$handsetData = $this->handsetFactory->create()->getCollection()->addFieldToFilter('valid_to',['gteq' => $now->format('Y-m-d H:i:s')])->addFieldToFilter('phone_sku',$subject['sku'])->getFirstItem();
    	if($handsetData['previous_phone_price']) {
        	return $handsetData['previous_phone_price'];
        }
        else {
        	return $result;
        }
    }

    public function afterGetSpecialPrice(\Magento\Catalog\Model\Product $subject, $result)
    {    
        $now = new \DateTime();
    	$handsetData = $this->handsetFactory->create()->getCollection()->addFieldToFilter('valid_to',['gteq' => $now->format('Y-m-d H:i:s')])->addFieldToFilter('phone_sku',$subject['sku'])->getFirstItem();
    	if($handsetData['current_phone_price']) {
        	return $handsetData['current_phone_price'];
        }
        else {
        	return $result;
        }
    }
}