<?php
namespace Panama\Order\Setup;
 
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
 
        if ($connection->tableColumnExists('sales_order', 'Invoiceuri') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'Invoiceuri',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Invoice Url'
                    ]
                );
        }
		if ($connection->tableColumnExists('sales_order', 'tracking_delivery_url') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'tracking_delivery_url',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'Delivery Tracking Url'
                    ]
                );
        }
		
        $installer->endSetup();
    }
}