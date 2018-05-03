<?php

namespace Panama\Checkout\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface {

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->addColumn(
                $installer->getTable('quote_item'), 'is_portable', [
            'type' => 'text',
            'nullable' => false,
            'comment' => 'is_portable',
                ]
        );
        $installer->getConnection()->addColumn(
                $installer->getTable('quote_item'), 'current_service', [
            'type' => 'text',
            'nullable' => false,
            'comment' => 'current_service',
                ]
        );
		$installer->getConnection()->addColumn(
                $installer->getTable('quote_item'), 'is_smartphone', [
            'type' => 'text',
            'nullable' => false,
            'comment' => 'is_smartphone',
                ]
        );

        $installer->getConnection()->addColumn(
                $installer->getTable('sales_order_item'), 'is_portable', [
            'type' => 'text',
            'nullable' => false,
            'comment' => 'is_portable',
                ]
        );
        $installer->getConnection()->addColumn(
                $installer->getTable('sales_order_item'), 'current_service', [
            'type' => 'text',
            'nullable' => false,
            'comment' => 'current_service',
                ]
        );
		$installer->getConnection()->addColumn(
                $installer->getTable('sales_order_item'), 'is_smartphone', [
            'type' => 'text',
            'nullable' => false,
            'comment' => 'is_smartphone',
                ]
        );
        $setup->endSetup();
    }
}
