<?php

namespace Panama\Abandoncart\Controller\Index;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $_categoryRepository;
	protected $_url;
	protected $_catalogSession;
	protected $_cart;
	protected $_transportBuilder;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
		\Magento\Framework\UrlInterface $url,
		\Magento\Catalog\Model\Session $catalogSession,
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Checkout\Model\Cart $cart
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
        $this->_categoryRepository = $categoryRepository;
		$this->_transportBuilder = $transportBuilder;
		$this->_url = $url;
		$this->_catalogSession = $catalogSession;
		$this->_cart = $cart;
	}

    public function execute() {
		
		$om = \Magento\Framework\App\ObjectManager::getInstance();
		$collection = $om->get('Magento\Reports\Model\ResourceModel\Quote\Collection');
		$store_id = $this->_storeManager->getStore()->getId();
		$collection->prepareForAbandonedReport([$store_id]);
		$rows = $collection->load();
		$abandonDatas = $rows->getData();
		count($abandonDatas);

		foreach($abandonDatas as $abandonData) {
			date_default_timezone_set('America/Martinique');
			$currentdate = date('Y-m-d h:i:s');
			$customerEmail = $abandonData['customer_email'];
			$customerFirstname = $abandonData['customer_firstname'];
			$customerLastname = $abandonData['customer_lastname'];
			$itemsCount = $abandonData['items_count'];
			$createdAt = $abandonData['created_at'];
			$updatedAt = $abandonData['updated_at'];
			$startTimeStamp = strtotime($updatedAt);
				
			if($startTimeStamp == '')
			   {
				   $startTimeStamp = strtotime($createdAt);
			   }
			   
			date_default_timezone_set('America/Martinique');
			$currentdate = date('Y-m-d h:i:s');
			//$startTimeStamp = strtotime("2018-05-05");
			$endTimeStamp = strtotime($currentdate);
            $timeDiff = abs($endTimeStamp - $startTimeStamp);
            $numberDays = $timeDiff/86400;  // 86400 seconds in one day
            $numberDays = intval($numberDays);

            if($numberDays >= 7){
				//Delete Quote
				$quoteID = $abandonData['entity_id'];
				//echo $this->deleteQuote($quoteID);	

			}else{
				$data['firstname'] =  $customerFirstname;
				$data['lastname']  =  $customerLastname;
				$data['itemscount']  =  $itemsCount;
				$data['days'] = 7 - $numberDays;
				$this->sendRemainder($data,$store_id,$customerEmail);				
			}			
				
		}
		echo  'successfully Done'; 
		
		
		
	}
	
	public function sendRemainder($data,$store_id,$customerEmail){
		$requestemailTemplate = 1;
	    $templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store_id);
		if ($requestemailTemplate) {
			$transport = $this->_transportBuilder
									->setTemplateIdentifier($requestemailTemplate)
									->setTemplateOptions($templateOptions)
									->setTemplateVars($data)
									->setFrom(array('name' => 'KarvannanP','email' => 'Karvannan@gmail.com'))
									->addTo($customerEmail)
									->getTransport();
				try{
                $transport->sendMessage();		
                }catch (\Exception $e) {
                        $message = array('valid' => 0,'message'=> $e->getMessage());
						print_r($message);
					}
				
			}
	}
	
	public function deleteQuote($quoteID){
		if($quoteID)
				{
					try {
						$quote = Mage::getModel("sales/quote")->load($quoteID);
						$quote->setIsActive(false);
						$quote->delete();

						return "cart deleted";
					} catch(Exception $e) {
						return $e->getMessage();
					}
				}else{
					return "no quote found";
				}
	}
}
