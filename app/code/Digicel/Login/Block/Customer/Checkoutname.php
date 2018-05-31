<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Digicel\Login\Block\Customer;


class Checkoutname extends \Magento\Customer\Block\Widget\Name
{
   
    public function _construct()
    {
        parent::_construct();

        // default template location
        $this->setTemplate('widget/checkoutname.phtml');
    }

   
}
?>
