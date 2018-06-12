<?php

namespace Digicel\BillingSystem\Controller\Index;

use Psr\Log\LoggerInterface;

class Index extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_tokenHelper;
    protected $_billingSystemHelper;
    protected $_checkoutSession;
    protected $_logger;

    /**
     * 
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Digicel\DigicelToken\Helper\Data $tokenHelper
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Digicel\DigicelToken\Helper\Data $tokenHelper, \Digicel\BillingSystem\Helper\Data $billingSystemHelper, \Magento\Checkout\Model\Session $checkoutSession, LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_tokenHelper = $tokenHelper;
        $this->_billingSystemHelper = $billingSystemHelper;
        $this->_checkoutSession = $checkoutSession;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        try {
		
            $tokens = $this->_tokenHelper->getTokens();
            $digicelApiUrl = $this->_billingSystemHelper->getConfig('panama/digicel_billingsystem_api_details/billing_url');
            $post = $this->getRequest()->getParams();

            $params = array('token' => $tokens, 'mobilenumber' => $post['mobilenumber'], 'creditcategory' => $post['creditcategory'], 'cardpackageid' => $post['cardpackageid'], 'firstname' => $post['firstname'], 'lastname' => $post['lastname'], 'address1' => $post['address1'], 'address2' => $post['address2'], 'address3' => $post['address3'], 'province' => $post['province'], 'email' => $post['email'], 'dob' => $post['dob'], 'doctype' => $post['doctype'], 'doc' => $post['doc'], 'primaryplan' => $post['primaryplan'], 'deposit' => $post['deposit'], 'question' => $post['question'], 'answer' => $post['answer'], 'planid' => $post['planid'], 'rk_aux_service' => $post['rk_aux_service'], 'trans_ref_cod' => $post['trans_ref_cod']);

            $billingSystemRequest = $this->_billingSystemHelper->getBillingSystemRequest($params);
            $billingSystemHeader = $this->_tokenHelper->getHeader($billingSystemRequest);

            $billingSystem = $this->_tokenHelper->getResponse($digicelApiUrl, $billingSystemRequest, $billingSystemHeader);
            if ($billingSystem) {
                $billingSystem = $this->_billingSystemHelper->parseResponse($billingSystem);

                $this->_tokenHelper->logResponse($billingSystem, 'billingsystem.log');

                $response = json_decode($this->jsonResponse($billingSystem));

                $responseValue = $response->soapBody->ACTIVACION_POSTPAGOResponse->ACTIVACION_POSTPAGOResult->diffgrdiffgram->DocumentElement->RESPUESTA;
                return $this->jsonResponse($responseValue);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
            $this->logger->critical($e);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '') {
        return $this->getResponse()->representJson(
                        $this->jsonHelper->jsonEncode($response)
        );
    }

}
