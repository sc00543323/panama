<?php
namespace Digicel\Portin\Controller\Terms;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Action\Context;

class Addressupdate extends \Magento\Framework\App\Action\Action {

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected  $directory_list;
	protected $_timezoneInterface;

    public function __construct(
    Context $context,
	\Magento\Framework\App\Filesystem\DirectoryList $directory_list,
	\Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
	) {
		$this->directory_list = $directory_list;
		$this->_timezoneInterface 	= $timezoneInterface;
		parent::__construct($context);
    }

    public function execute() {
		
		$truncate = "TRUNCATE TABLE panama_address;";
		$this->connectionEst()->query($truncate);
 
 
 $file = $this->directory_list->getPath("media")."/test/address.csv"; 

$table = $this->connectionEst()->getTableName('panama_address');

  $csv = array_map('str_getcsv', file($file));
    array_shift($csv); //remove headers
	$val = '';
	$cdata = '';
 foreach($csv as $data){
	 
	 foreach($data as $dat){
		 
		 if (!is_numeric($dat)) { 
		 $cdata .=  "'".$dat."',";
		 }else{
			 $cdata .=  $dat.",";
		 } 
	 }
	 $cdata = rtrim($cdata,",");
	 $val .= "(".$cdata."),";

	 $cdata = '';
 }
 $values = rtrim($val,",");
 

  $insert = "INSERT INTO panama_address (district_id,district_name,townShip_id,townShip_name,province_id,province_name) VALUES " . $values. ";";

 $this->connectionEst()->query($insert);
	echo 'Successfully Updated'; die;
		
    }
	
	 public function connectionEst() {
        $resource = $this->objectManagerInt()->get('Magento\Framework\App\ResourceConnection');
        return $resource->getConnection();
    }

    public function objectManagerInt() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objectManager;
    }

}

