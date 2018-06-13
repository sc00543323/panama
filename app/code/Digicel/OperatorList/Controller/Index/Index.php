<?php

namespace Digicel\OperatorList\Controller\Index;

use Psr\Log\LoggerInterface;

class Index extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_tokenHelper;
    protected $_operatorListHelper;
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
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Digicel\DigicelToken\Helper\Data $tokenHelper, \Digicel\OperatorList\Helper\Data $operatorhelper, \Magento\Checkout\Model\Session $checkoutSession, LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_tokenHelper = $tokenHelper;
        $this->_operatorListHelper = $operatorhelper;
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
            $digicelApiUrl = $this->_operatorListHelper->getConfig('panama/digicel_operator_api_details/operator_url');
            //$post = $this->getRequest()->getParams();

            $params = array('token' => $tokens);

            $operatorListRequest = $this->_operatorListHelper->getOperatorListRequest($params);
            $operatorListHeader = $this->_tokenHelper->getHeader($operatorListRequest);

            $operatorList = $this->_tokenHelper->getResponse($digicelApiUrl, $operatorListRequest, $operatorListHeader);
            if ($operatorList) {
                $operatorList = $this->_operatorListHelper->parseResponse($operatorList);

                $this->_tokenHelper->logResponse($operatorList, 'operatorlist.log');

                $response = json_decode($this->jsonResponse($operatorList));

                $responseValue = $response->soapBody->LNP_LISTADO_OPERADORESResponse->LNP_LISTADO_OPERADORESResult->diffgrdiffgram->DocumentElement->RESPUESTA;
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
