<?php
namespace Panama\Handset\Model\ResourceModel\Handset;

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
            'Panama\Handset\Model\Handset',
            'Panama\Handset\Model\ResourceModel\Handset'
        );
    }
}
