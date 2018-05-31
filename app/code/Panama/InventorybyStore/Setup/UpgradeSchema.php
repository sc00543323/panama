<?php

namespace Panama\InventorybyStore\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface {

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $installer = $setup;
            $installer->startSetup();
            $stocklistTable = $installer->getTable('limesharp_stockists_stores');
            $stocklistColumns = [
                'new_store_id' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Store Id',
                ]
            ];
            $connection = $installer->getConnection();
            foreach ($stocklistColumns as $name => $definition) {
                $connection->addColumn($stocklistTable, $name, $definition);
            }
            $installer->endSetup();
        }
    }
}
