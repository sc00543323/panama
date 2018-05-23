<?php

namespace Digicel\DigicelToken\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface {

    /**
     * {@inheritdoc}
     */
    public function upgrade(
    SchemaSetupInterface $setup, ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $table_digiceltoken = $setup->getConnection()->newTable($setup->getTable('digiceltoken'));

        $table_digiceltoken->addColumn(
                'digiceltoken_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, array('identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true), 'Entity ID'
        );

        $table_digiceltoken->addColumn(
            'token_response',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'token_response'
        );
        
        $table_digiceltoken->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [],
            'created_at'
        );
        $setup->getConnection()->createTable($table_digiceltoken);
        
        $setup->endSetup();
    }

}
