<?php
  
namespace Panama\Handset\Setup;
  
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
  
        // Get handset_price table
        $tableName = $installer->getTable('handset_price');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create handset_price table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'plan_id',
                    Table::TYPE_INTEGER,
                    null,
                    [],
                    'Plan Id'
                )
                ->addColumn(
                    'monthly_service_price',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'Monthly Service Price'
                )
                ->addColumn(
                    'monthly_handset_price',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'Monthly Hand set Price'
                )
                ->addColumn(
                    'phone_sku',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Phone SKU'
                )
                ->addColumn(
                    'previous_phone_price',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'Previous Phone Price (Prepaid)'
                )
                ->addColumn(
                    'current_phone_price',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'Current Phone Price(Prepaid)'
                )
                ->addColumn(
                    'postpaid_phone_price',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'Postpaid Phone Price'
                )
                ->addColumn(
                    'prepaid_phone_price_with_port_in',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'Prepaid Phone price with port-in'
                )
                ->addColumn(
                    'postpaid_phone_price_with_port_in',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'Postpaid Phone price with port-in'
                )
                ->addColumn(
                    'down_payment_amount',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'Down payment Amount'
                )
                ->addIndex(
                    $installer->getIdxName(
                        'handset_price',
                        ['phone_sku'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['phone_sku'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Handset Price Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
  
        $installer->endSetup();
    }
}
