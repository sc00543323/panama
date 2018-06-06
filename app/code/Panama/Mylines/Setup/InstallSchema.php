<?php
namespace Panama\Mylines\Setup;
 
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

        if ($connection->tableColumnExists('sales_order', 'msisdn_status') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'msisdn_status',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'MSISDN Status'
                    ]
                );
        }
		
		if ($connection->tableColumnExists('sales_order', 'msisdn') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'msisdn',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'comment' => 'MSISDN Number'
                    ]
                );
        }
		
        $installer->endSetup();
    }
}