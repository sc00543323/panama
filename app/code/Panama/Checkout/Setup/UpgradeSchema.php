<?php

namespace Panama\Checkout\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface {

    public function Upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $installer = $setup;

            $installer->startSetup();

            $orderTable = $installer->getTable('quote_item');

            $orderColumns = [
                'is_contract' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Is Contract',
                ],
            ];
            $connection = $installer->getConnection();
            foreach ($orderColumns as $name => $definition) {
                $connection->addColumn($orderTable, $name, $definition);
            }
            $installer->endSetup();

            $installer->startSetup();
            $tableName = $installer->getTable('sales_order_item');

            $connection = $installer->getConnection();
            $connection->addColumn(
                    $tableName, 'is_contract', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => false,
					'comment' => 'Is Contract']
            );
            $installer->endSetup();
        }
        
}
}
