<?php
namespace Panama\Offlinepayment\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        if ($connection->tableColumnExists('sales_order', 'order_payment_confirm') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'order_payment_confirm',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Order Payment Confirm'
                    ]
                );
        }
		if ($connection->tableColumnExists('sales_order', 'payment_type') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'payment_type',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Order Payment Type'
                    ]
                );
        }
		if ($connection->tableColumnExists('sales_order', 'confirmation_number') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'confirmation_number',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Payment Confirmation Number'
                    ]
                );
        }
		if ($connection->tableColumnExists('sales_order', 'paid_on') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'paid_on',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        'length' => 255,
                        'comment' => 'Order Payment Date'
                    ]
                );
        }
		if ($connection->tableColumnExists('sales_order', 'result_id') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'result_id',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Result Id'
                    ]
                );
        }
		if ($connection->tableColumnExists('sales_order', 'result_message') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'result_message',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Result Message'
                    ]
                );
        }
        $installer->endSetup();
    }
}