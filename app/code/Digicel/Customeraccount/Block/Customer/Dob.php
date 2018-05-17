<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Digicel\Customeraccount\Block\Customer;

class Dob extends \Magento\Customer\Block\Widget\Dob
{
   
    public function _construct()
    {
        parent::_construct();

        // default template location
        $this->setTemplate('widget/dob.phtml');
    }
     public function getFieldHtml()
    {
		$value=$this->getValue();
		$format='d/m/Y';
		$value = (strpos($this->getValue(), '-') !== false) ? date($format,strtotime($this->getValue())) :  $this->getValue();
        $this->dateElement->setData([
            'extra_params' => $this->isRequired() ? 'data-validate="{required:true,\'validate-date-au\':true, \'validate-minimum-age\':true}"' : '',
            'name' => $this->getHtmlId(),
            'id' => $this->getHtmlId(),
            'class' => $this->getHtmlClass(),
            'value' => $value,
            'date_format' => 'dd/mm/yy',
            'image' => $this->getViewFileUrl('images/calender2_icon.png'),
            'years_range' => '-120y:c+nn',
            'max_date' => '-1d',
            'change_month' => 'true',
            'change_year' => 'true',
            'show_on' => 'both'
        ]);
       // echo get_class($this->dateElement);
        return $this->dateElement->getHtml1();
    }
	public function getFieldHtml1()
    {
	
		$value=$this->getValue();
		$format='d/m/Y';
		$value = (strpos($this->getValue(), '-') !== false) ? date($format,strtotime($this->getValue())) :  $this->getValue();
        $this->dateElement->setData([
            'extra_params' => $this->isRequired() ? 'data-validate="{required:true,\'validate-date-au\':true, \'validate-minimum-age\':true}"' : '',
            'name' => $this->getHtmlId(),
            'id' => $this->getHtmlId(),
            'class' => $this->getHtmlClass(),
            'value' => $value,
            'date_format' => 'dd/mm/yy',
            'image' => $this->getViewFileUrl('images/calender2_icon.png'),
            'years_range' => '-120y:c+nn',
            'max_date' => '-1d',
            'change_month' => 'true',
            'change_year' => 'true',
            'show_on' => 'both'
        ]);
        //echo get_class($this->dateElement);
        return $this->dateElement->getHtml3();
    }   
}
?>
