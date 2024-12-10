<?php

namespace Perspective\Holidays\Setup;

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

        if (!$installer->tableExists('perspective_holidays')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('perspective_holidays')
            )
                ->addColumn(
                    'holiday_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Holiday ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Holiday Name'
                )
                ->addColumn(
                    'period_start',
                    Table::TYPE_DATE,
                    null,
                    ['nullable' => true],
                    'Period Start'
                )
                ->addColumn(
                    'exact_date',
                    Table::TYPE_DATE,
                    null,
                    ['nullable' => true],
                    'Exact Date'
                )
                ->addColumn(
                    'period_end',
                    Table::TYPE_DATE,
                    null,
                    ['nullable' => true],
                    'Period End'
                )
                ->addColumn(
                    'greeting_text',
                    Table::TYPE_TEXT,
                    '64k',
                    ['nullable' => false],
                    'Greeting Text'
                )
                ->addColumn(
                    'is_active',
                    Table::TYPE_BOOLEAN,
                    null,
                    ['nullable' => false, 'default' => 1],
                    'Is Active'
                )
                ->addColumn(
                    'store_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => 0],
                    'Store ID'
                )
                ->addColumn(
                    'discount_percentage',
                    Table::TYPE_DECIMAL,
                    null,
                    ['nullable' => false, 'default' => 0],
                    'Discount Percentage',
                )
                ->addColumn(
                    'allowed_customer_group',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => 0],
                    'Allowed Customer Groups (comma-separated IDs)'
                )
                ->setComment('Holidays Table');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
