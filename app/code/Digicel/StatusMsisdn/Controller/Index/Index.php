<?php

namespace Digicel\StatusMsisdn\Controller\Index;

use Psr\Log\LoggerInterface;

class Index extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_tokenHelper;
    protected $_statusMsisdnHelper;
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
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Digicel\DigicelToken\Helper\Data $tokenHelper, \Digicel\StatusMsisdn\Helper\Data $statusMsisdnHelper, \Magento\Checkout\Model\Session $checkoutSession, LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_tokenHelper = $tokenHelper;
        $this->_statusMsisdnHelper = $statusMsisdnHelper;
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
            $digicelApiUrl = $this->_statusMsisdnHelper->getConfig('panama/digicel_statusmsisdn_api_details/statusmsisdn_url');
            //$post = $this->getRequest()->getParams();

            $params = array('token' => $tokens);

            $statusMsisdnRequest = $this->_statusMsisdnHelper->getStatusMsisdnRequest($params);
            $statusMsisdnHeader = $this->_tokenHelper->getHeader($statusMsisdnRequest);

            $statusMsisdn = $this->_tokenHelper->getResponse($digicelApiUrl, $statusMsisdnRequest, $statusMsisdnHeader);
            if ($statusMsisdn) {
                $statusMsisdn = $this->_statusMsisdnHelper->parseResponse($statusMsisdn);

                $this->_tokenHelper->logResponse($statusMsisdn, 'statusmsisdn.log');

                $response = json_decode($this->jsonResponse($statusMsisdn));

                $responseValue = $response->soapBody->STATUS_MSISDNResponse->STATUS_MSISDNResult->diffgrdiffgram->DocumentElement->RESPUESTA;
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
