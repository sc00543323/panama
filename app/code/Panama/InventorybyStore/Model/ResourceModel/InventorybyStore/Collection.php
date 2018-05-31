<?php
namespace Panama\InventorybyStore\Model\ResourceModel\InventorybyStore;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            'Panama\InventorybyStore\Model\InventorybyStore',
            'Panama\InventorybyStore\Model\ResourceModel\InventorybyStore'
        );
    }
}
