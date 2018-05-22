<?php
 
namespace Digicel\Swipe\Model;
 
/**
 * Pay In Store payment method model
 */
class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
 
    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'custompayment';
	protected $_availablecreditlimit = 10000000;
	
	public function getAvailableCreditLimit(){
	
	return $this->_availablecreditlimit;
	
	}
	
	
	
}