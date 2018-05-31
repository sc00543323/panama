<?php

namespace Digicel\DisplayLogo\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class DisplayLogo extends Template {

    protected $_categoryCollection;
    protected $categoryRepository;
    protected $_registry;
    protected $_storeManager;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection, CategoryRepositoryInterface $categoryRepository, \Magento\Framework\Registry $registry
    ) {
        $this->_categoryCollection = $categoryCollection;
        $this->_storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->_registry = $registry;
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getCatCollection() {

        $id = $this->getCurrentCategory()->getId();

        if ($id == 4) {
            $childIds = $this->getCurrentCategory()->getChildren();
            $collection = $this->_categoryCollection->create()->addAttributeToSelect('*')->addAttributeToFilter('entity_id',array('in'=>explode(',',$childIds)));
                      
            return $collection;
        }
    }

    public function getCategory($id) {
        $category = $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());

        return $category->getUrl();
    }

    public function getCurrentCategory() {
        return $this->_registry->registry('current_category');
    }

     public function getMediaUrl() {
        $mediaUrl = $this->_storeManager
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
}
