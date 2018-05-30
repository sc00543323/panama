<?php


namespace Digicel\Login\Model\ResourceModel\Data;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
	    'Digicel\Login\Model\Data',
	    'Digicel\Login\Model\ResourceModel\Data'
	);
    }
}