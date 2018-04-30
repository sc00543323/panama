<?php
 
namespace Digicel\Customeraccount\Setup;
 
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;
 
class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
	//private $_attributeRepository;
     
    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory, \Magento\Eav\Model\Config $eavConfig)
    {
        $this->_eavSetupFactory = $eavSetupFactory;
		//$this->_attributeRepository = $attributeRepository;
		$this->eavConfig  = $eavConfig;
    }
 
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'cedulla',
            [
                'type'         => 'varchar',
                'label'        => 'Cedulla',
                'input'        => 'text',
                'required'     => true,
                'visible'      => true,
                'user_defined' => true,
                'position'     => 909,
                'system'       => 0,
            ]
        );
		$eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'passport',
            [
                'type'         => 'varchar',
                'label'        => 'Passport',
                'input'        => 'text',
                'required'     => true,
                'visible'      => true,
                'user_defined' => true,
                'position'     => 919,
                'system'       => 0,
            ]
        );
        // allow customer_attribute attribute to be saved in the specific areas
		$attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'cedulla');
		$attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'passport');
 
		//$attribute = $this->_attributeRepository->get('customer', 'cedulla');
		//$attribute = $this->_attributeRepository->get('customer', 'passport');
		$setup->getConnection()
		->insertOnDuplicate(
			$setup->getTable('customer_form_attribute'),
			[
				['form_code' => 'adminhtml_customer', 'attribute_id' => $attribute->getId()],
				['form_code' => 'customer_account_create', 'attribute_id' => $attribute->getId()],
				['form_code' => 'customer_account_edit', 'attribute_id' => $attribute->getId()],
			]
		);
		$attribute->save();
    }
}