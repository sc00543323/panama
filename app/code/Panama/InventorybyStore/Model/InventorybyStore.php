<?php
namespace Panama\InventorybyStore\Model;

class InventorybyStore extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Panama\InventorybyStore\Model\ResourceModel\InventorybyStore');
    }
}