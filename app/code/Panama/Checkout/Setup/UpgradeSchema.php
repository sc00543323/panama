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
		if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $installer = $setup;

            $installer->startSetup();

            $orderTable = $installer->getTable('sales_order');

            $orderColumns = [
                'tracking_delivery_url' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Tracking Delivery Url',
                ],
				'invoice_url' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Invoice Url',
                ],
				'order_payment_confirm' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Order Payment Confirm',
                ],
				'confirmation_number' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Confirmation Number',
                ],
				'payment_type' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Payment Type',
                ],
				'paid_on' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Paid On',
                ]
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
                    $tableName, 'serial_id', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => false,
					'comment' => 'Serial Id']
            );
			$connection->addColumn(
                    $tableName, 'msisdn', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => false,
					'comment' => 'Msisdn']
            );
			$connection->addColumn(
                    $tableName, 'porting_status_id', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => false,
					'comment' => 'Porting Status Id']
            );
            $installer->endSetup();
        }
		if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $installer = $setup;

            $installer->startSetup();
            $tableName = $installer->getTable('sales_order_item');

            $connection = $installer->getConnection();
            $connection->addColumn(
                    $tableName, 'associate_product_id', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => false,
					'comment' => 'Associate Product Id']
            );
            $installer->endSetup();
			
			$installer->startSetup();
            $tableName = $installer->getTable('quote_item');

            $connection = $installer->getConnection();
            $connection->addColumn(
                    $tableName, 'associate_product_id', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => false,
					'comment' => 'Associate Product Id']
            );
            $installer->endSetup();
        }
        
}
}
