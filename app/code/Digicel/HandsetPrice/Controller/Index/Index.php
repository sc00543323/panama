<?php

namespace Digicel\HandsetPrice\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_tokenHelper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Digicel\DigicelToken\Helper\Data $tokenHelper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_tokenHelper = $tokenHelper;
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

            $post = $this->getRequest()->getParams();
            $params = array('token' => $tokens, 'PlanID' => $post['PlanID'], 'Trans_Type' => $post['Trans_Type'], 'SKU_ID' => $post['SKU_ID']);
            $digicelApiUrl = $this->_tokenHelper->getHandsetUrl();
            $handsetPriceRequest = $this->getHandsetPriceRequest($params);
            $handsetPriceHeader = $this->_tokenHelper->getHeader($handsetPriceRequest);
            $handsetPrice = $this->_tokenHelper->getResponse($digicelApiUrl, $handsetPriceRequest, $handsetPriceHeader);
            $result = $this->getPriceResponse($handsetPrice);
            return $this->jsonResponse($result['Price']);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            //$this->logger->critical($e);
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

    public function getHandsetPriceRequest($input) {
        $handsetPriceRequest = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <AuthHeader xmlns="http://digicelpanama.com/">
      <Token>' . $input["token"] . '</Token>
    </AuthHeader>
  </soap:Header>
  <soap:Body>
    <HANDSET_PRICE xmlns="http://digicelpanama.com/">
      <PlanID>' . $input["PlanID"] . '</PlanID>
      <Trans_Type>' . $input["Trans_Type"] . '</Trans_Type>
      <SKU_ID>' . $input["SKU_ID"] . '</SKU_ID>
    </HANDSET_PRICE>
  </soap:Body>
</soap:Envelope>';
        return $handsetPriceRequest;
    }

    public function getPriceResponse($string) {

        $domDocument = new DOMDocument();
        $domDocument->loadXML($string);
        $result = array();
        foreach ($domDocument->getElementsByTagName("codigo") as $codigoElement) {
            $result["codigo"] = $codigoElement->textContent;
        }
        foreach ($domDocument->getElementsByTagName("Price") as $resultElement) {
            $result["Price"] = $resultElement->textContent;
        }

        return $result;
    }

}
