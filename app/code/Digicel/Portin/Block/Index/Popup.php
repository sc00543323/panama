<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Digicel\Portin\Block\Index;


class Popup extends \Magento\Framework\View\Element\Template
{
   
    public function _construct()
    {
        parent::_construct();

        // default template location
        $this->setTemplate('Popup.phtml');
    }

   public function getcustomer(){		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerSession = $objectManager->get('Magento\Customer\Model\Session');
		$customerId = $customerSession->getCustomer()->getId();
		return $customerId;
	}
}
?>
