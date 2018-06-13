<?php

namespace Digicel\Nip\Controller\Index;

use Psr\Log\LoggerInterface;

class Index extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_tokenHelper;
    protected $_nipHelper;
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
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Digicel\DigicelToken\Helper\Data $tokenHelper, \Digicel\Nip\Helper\Data $nipHelper, \Magento\Checkout\Model\Session $checkoutSession, LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_tokenHelper = $tokenHelper;
        $this->_nipHelper = $nipHelper;
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
            $digicelApiUrl = $this->_nipHelper->getConfig('panama/digicel_nip_api_details/nip_url');
            //$post = $this->getRequest()->getParams();

            $params = array('token' => $tokens);

            $nipRequest = $this->_nipHelper->getNipRequest($params);
            $nipHeader = $this->_tokenHelper->getHeader($nipRequest);

            $nip = $this->_tokenHelper->getResponse($digicelApiUrl, $nipRequest, $nipHeader);
            if ($nip) {
                $nip = $this->_nipHelper->parseResponse($nip);

                $this->_tokenHelper->logResponse($nip, 'nip.log');

                $response = json_decode($this->jsonResponse($nip));

                $responseValue = $response->soapBody->LNP_ASEP_NIP_REQUESTResponse->LNP_ASEP_NIP_REQUESTResult->diffgrdiffgram->DocumentElement->RESPUESTA;
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
