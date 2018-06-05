<?php
	 
namespace Digicel\Login\Model\ResourceModel;


use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;
	 
class Data extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('panama_address', 'address_id');
    }
}