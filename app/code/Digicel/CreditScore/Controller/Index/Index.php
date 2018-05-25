<?php

namespace Digicel\CreditScore\Controller\Index;

use Psr\Log\LoggerInterface;

class Index extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_tokenHelper;
    protected $_creditScoreHelper;
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
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Digicel\DigicelToken\Helper\Data $tokenHelper, \Digicel\CreditScore\Helper\Data $credithelper,\Magento\Checkout\Model\Session $checkoutSession,LoggerInterface $logger
            
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_tokenHelper = $tokenHelper;
        $this->_creditScoreHelper = $credithelper;
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
//            return $this->jsonResponse(array(
//                        'color_code' => '<div style="float: left;width: 20px;height: 20px;margin: 5px;border: 1px solid rgba(0, 0, 0, .2);background: #66cc80;" ></div>'
//            ));
            $tokens = $this->_tokenHelper->getTokens();
            $digicelApiUrl = $this->_creditScoreHelper->getConfig('panama/digicel_handset_api_details/handset_url');
            $post = $this->getRequest()->getParams();

            $params = array('token' => $tokens, 'Customer_ID' => $post['Customer_ID'], 'DocumentType' => $post['DocumentType'], 'Handset_Cost' => $post['Handset_Cost'], 'Handset_Model' => $post['Handset_Model']);

            $creditScoreRequest = $this->_creditScoreHelper->getCreditScoreRequest($params);
            $creditScoreHeader = $this->_tokenHelper->getHeader($creditScoreRequest);

            $creditScore = $this->_tokenHelper->getResponse($digicelApiUrl, $creditScoreRequest, $creditScoreHeader);

            $creditScore = $this->_creditScoreHelper->parseResponse($creditScore);

            $this->_tokenHelper->logResponse($creditScore, 'creditscore.log');
            
            $response = json_decode($this->jsonResponse($creditScore));
            
            $codigoValue = $response->soapBody->CREDIT_SCORINGResponse->CREDIT_SCORINGResult->diffgrdiffgram->DocumentElement->RESPUESTA->Codigo;
            if ($codigoValue == 00) {
                $color = "#66cc80";
            } else if ($codigoValue == 01) {
                $color = "#e6b366";
            } else {
                $color = '#e66666';
            }
            $this->_checkoutSession->setCreditColorCode($color);
            return $this->jsonResponse(array(
                        'color_code' => '<div style="float: left;width: 20px;height: 20px;margin: 5px;border: 1px solid rgba(0, 0, 0, .2);background: '.$color.';" ></div>'
            ));
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
