<?php

namespace Panama\Checkout\Controller\Cart;

class ExtendDelete extends \Magento\Checkout\Controller\Cart\Delete
{
    /**
     * Delete shopping cart item action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
	
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
			
				//start unset all portable option session 
				$catalogSession = $this->_objectManager->create('Magento\Catalog\Model\Session');
				$catalogSession->unsPortProductId();
				$catalogSession->unsPort();
				$catalogSession->unsCurrentService();
				$catalogSession->unsBuySmartphone();
				$catalogSession->unsContract();
				//end unset all portable option session 
			
				/*$currentItem = $this->_objectManager->create('Magento\Quote\Model\Quote\Item')->load($id);
				$currentItemIdAssociate = $currentItem->getAssociateProductId();
				$cart = $this->_objectManager->get('\Magento\Checkout\Model\Cart'); 
				$allItems = $cart->getQuote()->getAllVisibleItems();
				foreach ($allItems as $item) {
					if(($currentItemIdAssociate == $item->getProductId()) && $item->getAssociateProductId()) {
						//$quoteItem = $this->_objectManager->create('Magento\Quote\Model\Quote\Item')->load($item->getId());
						//$quoteItem->delete();//deletes the item
						$removedId = $item->getItemId();
						$this->cart->removeItem($removedId)->save();
						break;
					}
				}*/
                $this->cart->removeItem($id)->save();
            } catch (\Exception $e) {
                $this->messageManager->addError(__('We can\'t remove the item.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            }
        }
        $defaultUrl = $this->_objectManager->create(\Magento\Framework\UrlInterface::class)->getUrl('*/*');
        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl($defaultUrl));
    }
	
}
