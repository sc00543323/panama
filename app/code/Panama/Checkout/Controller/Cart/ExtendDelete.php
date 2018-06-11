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
				$removedIds = array();
				$currentItem = $this->_objectManager->create('Magento\Quote\Model\Quote\Item')->load($id);
				$currentItemIdAssociate = $currentItem->getAssociateProductId();
				$allItems = $this->cart->getQuote()->getAllVisibleItems();
				$i=0;				
				$quoteId = '';
				foreach ($allItems as $item) {
					$quoteId = $item->getQuoteId();
					if(($currentItemIdAssociate == $item->getProductId()) && $item->getAssociateProductId() && $i == 0) {
						$removedId = $item->getItemId();
						$removedIds[] = $removedId;
						$i = 1;
					}
				}
				$removedIds[] = $id;
				foreach ($removedIds as $id) {
					$quoteItem = $this->_objectManager->create('Magento\Quote\Model\Quote\Item')->load($id);
					$quoteItem->delete()->save();
					//$this->cart->removeItem($id)->save();
				}
				$quote = $this->_objectManager->create('Magento\Quote\Model\Quote')->load($quoteId);
				$allVisItem = $quote->getAllVisibleItems();
				$j=0;
				foreach($allVisItem as $item) {
					$j++;
				}
				if($j == 0) {
					$quote = $this->_objectManager->create('Magento\Quote\Model\Quote')->load($quoteId);
					$quote->delete()->save();
				}
				
            } catch (\Exception $e) {
                $this->messageManager->addError(__('We can\'t remove the item.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            }
        }
        $defaultUrl = $this->_objectManager->create(\Magento\Framework\UrlInterface::class)->getUrl('*/*');
        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl($defaultUrl));
    }
	
}
