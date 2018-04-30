<?php
/**
 * Copyright © 2015 Digicel. All rights reserved.
 */
namespace Digicel\Customeraccount\Model\ResourceModel;

/**
 * Smsdetails resource
 */
class Cedulla extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('cedulla', 'id');
    }

  
}
