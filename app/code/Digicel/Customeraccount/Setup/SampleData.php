<?php
namespace Digicel\Customeraccount\Setup;

use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
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

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), 'VERSION') < 0) {
            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'cedulla', [
               'type'         => 'varchar',
                'label'        => 'Cedulla',
                'input'        => 'text',
                'required'     => true,
                'visible'      => true,
                'user_defined' => true,
                'position'     => 909,
                'system'       => 0,
            ]);
			$customerSetup->addAttribute(Customer::ENTITY, 'passport', [
               'type'         => 'varchar',
                'label'        => 'Passport',
                'input'        => 'text',
                'required'     => true,
                'visible'      => true,
                'user_defined' => true,
                'position'     => 909,
                'system'       => 0,
            ]);


            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'cedulla')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
					'used_in_forms' => ['customer_account_create'],
					'used_in_forms' => ['customer_account_edit']
                ]);
				
			$attribute->save();
			$attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'passport')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
					'used_in_forms' => ['customer_account_create'],
					'used_in_forms' => ['customer_account_edit']
                ]);
            $attribute->save();
        }
    }
}