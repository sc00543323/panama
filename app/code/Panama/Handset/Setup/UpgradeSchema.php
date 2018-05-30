<?php

namespace Panama\Handset\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface {

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $installer = $setup;
            $installer->startSetup();
            $handsetTable = $installer->getTable('handset_price');
            $handsetColumns = [
                'color' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Color',
                ],
                'created_date' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    'nullable' => false,
                    'comment' => 'Created Date',
                ],
                'created_by' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Created By',
                ],
                'valid_from' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    'nullable' => false,
                    'comment' => 'Valid from',
                ],
                'valid_to' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    'nullable' => false,
                    'comment' => 'Valid To',
                ],
                'comment' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    'nullable' => false,
                    'comment' => 'Comment',
                ],
            ];
            $connection = $installer->getConnection();
            foreach ($handsetColumns as $name => $definition) {
                $connection->addColumn($handsetTable, $name, $definition);
            }
            $installer->endSetup();
        }
    }
}
