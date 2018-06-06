<?php

namespace Digicel\Portin\Controller\Terms;
use Magento\Framework\View\Result\PageFactory;

class Getname extends \Magento\Framework\App\Action\Action {

	protected $_storeManager;
	protected $resultPageFactory;
	protected $_downloader;
	protected $_directory;
	
    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem\DirectoryList $directory,
		PageFactory $resultPageFactory
	){
		parent::__construct($context);
		$this->_storeManager = $storeManager;
		$this->resultPageFactory = $resultPageFactory;
		$this->_downloader =  $fileFactory;
        $this->directory = $directory;
	}

    public function execute() {
	
	    echo $fileName = 'invoice2018-05-10_14-36-00.pdf'; die;
        $file = $this->directory->getPath("media")."/test/default/".$fileName;
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false); // required for certain browsers 
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename="'. $file . '";');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		echo 'Success'; die; 
		
    }
}
