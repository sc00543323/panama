<?php
namespace Panama\Handset\Model;

class Handset extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Panama\Handset\Model\ResourceModel\Handset');
    }
}