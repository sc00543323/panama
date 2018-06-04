<?php
namespace Panama\Customer\Block\Form;

class Login extends \Magento\Customer\Block\Form\Login
{
    public function getCreateAccountUrl()
    {
        $block = $this->getLayout()->createBlock('Magento\Customer\Block\Form\Login\Info');
        return $block->getCreateAccountUrl();
    }
}
