<?php
namespace test\CustomAttribute\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }


    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(Customer::ENTITY, 'cedulla', [
            'type' => 'varchar',
            'label' => 'Cedulla',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'position' =>979,
            'system' => 0,
        ]);
		$customerSetup->addAttribute(Customer::ENTITY, 'passport', [
            'type' => 'varchar',
            'label' => 'Passport',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'position' =>988,
            'system' => 0,
        ]);
		$customerSetup->addAttribute(Customer::ENTITY, 'mobile_number', [
            'type' => 'varchar',
            'label' => 'Mobile Number',
            'input' => 'text',
            'required' => true,
            'visible' => true,
            'user_defined' => true,
            'position' =>998,
            'system' => 0,
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'cedulla')
        ->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit']
        ]);
		
		$attribute->save();
		
		$attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'passport')
        ->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit']
        ]);
		
		$attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'mobile_number')
        ->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit']
        ]);

        $attribute->save();
    }
}