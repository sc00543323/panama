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
	
		//start unset all portable option session 
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$catalogSession = $objectManager->create('Magento\Catalog\Model\Session');
		$catalogSession->unsPortProductId();
		$catalogSession->unsPort();
		$catalogSession->unsCurrentService();
		$catalogSession->unsBuySmartphone();
		$catalogSession->unsContract();
		//end unset all portable option session 
		
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
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
