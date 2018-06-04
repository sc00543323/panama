<?php

namespace Digicel\Login\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface {

    public function Upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        if (version_compare($context->getVersion(), '0.0.3') < 0) {
			$installer = $setup;
			$installer->startSetup();
			$table = $installer->getConnection()
            ->newTable($installer->getTable('panama_address'))
            ->addColumn(
                'address_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Address Id'
            )
            ->addColumn(
                'district_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0'],
                'District ID'
            )
			->addColumn(
                'district_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                150,
                ['nullable' => true, 'default' => null],
                'District Name'
            )
			->addColumn(
                'townShip_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0'],
                'TownShip ID'
            )
            ->addColumn(
                'townShip_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                150,
                ['nullable' => true, 'default' => null],
                'TownShip Name'
            )
			->addColumn(
                'province_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0'],
                'Province Id'
            )
			->addColumn(
                'province_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                150,
                ['nullable' => true, 'default' => null],
                'Province Name'
            )
			->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Created At'
			)
			->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Updated At'
			)
			->addIndex(
                $installer->getIdxName('panama_address', ['address_id']),
                ['address_id']
            )
            ->setComment('Panama Address');
        $installer->getConnection()->createTable($table);

        }
        
		/* End Address*/
}
}
