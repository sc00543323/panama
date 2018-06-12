<?php

namespace Digicel\NipAgain\Controller\Index;

use Psr\Log\LoggerInterface;

class Index extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_tokenHelper;
    protected $_nipAgainHelper;
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
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Digicel\DigicelToken\Helper\Data $tokenHelper, \Digicel\NipAgain\Helper\Data $nipAgainHelper, \Magento\Checkout\Model\Session $checkoutSession, LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_tokenHelper = $tokenHelper;
        $this->_nipAgainHelper = $nipAgainHelper;
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
            $digicelApiUrl = $this->_nipAgainHelper->getConfig('panama/digicel_nipagain_api_details/nipagain_url');
            //$post = $this->getRequest()->getParams();

            $params = array('token' => $tokens);

            $nipAgainRequest = $this->_nipAgainHelper->getNipAgainRequest($params);
            $nipAgainHeader = $this->_tokenHelper->getHeader($nipAgainRequest);

            $nipAgain = $this->_tokenHelper->getResponse($digicelApiUrl, $nipAgainRequest, $nipAgainHeader);
            if ($nipAgain) {
                $nipAgain = $this->_nipAgainHelper->parseResponse($nipAgain);

                $this->_tokenHelper->logResponse($nipAgain, 'nipagain.log');

                $response = json_decode($this->jsonResponse($nipAgain));

                $responseValue = $response->soapBody->LNP_ASEP_NIP_REQUEST_AGAINResponse->LNP_ASEP_NIP_REQUEST_AGAINResult->diffgrdiffgram->DocumentElement->RESPUESTA;
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
