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
		
		//$truncate = "TRUNCATE TABLE Panama_address;";
		//$this->connectionEst()->query($truncate);
		
		 $query = "CREATE TABLE Panama_address (
  address_id int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Address Id',
  district_id int(10) unsigned NOT NULL COMMENT 'District ID',
  district_name varchar(255) NOT NULL COMMENT 'District Name',
  townShip_id int(10) unsigned NOT NULL COMMENT 'TownShip ID',
  townShip_name varchar(255) NOT NULL COMMENT 'TownShip Name',
  province_id int(10) unsigned NOT NULL COMMENT 'Province ID',
  province_name varchar(255) NOT NULL COMMENT 'Province Name',
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation Time',
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',
  PRIMARY KEY (address_id)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Catalog Category Table';";

 $this->connectionEst()->query($query); 
 
 
 
 $file = $this->directory_list->getPath("media")."/test/address.csv"; 

$table = $this->connectionEst()->getTableName('Panama_address');

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
 

  $insert = "INSERT INTO Panama_address (district_id,district_name,townShip_id,townShip_name,province_id,province_name) VALUES " . $values. ";";

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

