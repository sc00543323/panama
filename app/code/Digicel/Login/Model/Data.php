<?php


namespace Digicel\Login\Model;
	 
use Magento\Framework\Model\AbstractModel;
	 
	class Data extends AbstractModel
	{	
	    protected function _construct()
	    {
	        $this->_init('Digicel\Login\Model\ResourceModel\Data');
	    }
	}