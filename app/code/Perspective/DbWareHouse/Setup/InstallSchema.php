<?php
namespace Perspective\DbWareHouse\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if(!$installer->tableExists("perspective_warehouse"))
        {
            $table = $installer->getConnection()
            ->newTable($installer->getTable('perspective_warehouse'))
            ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'ID')
            ->addColumn('name_war', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20, [], 'Warehouse Name')
            ->addColumn('addr_war', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 50, [], 'Warehouse address')
            ->addColumn('id_cat', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20, [], 'Category ID')
            ->addColumn('id_prod', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20, [], 'Product ID')
            ->addColumn('kol_prod', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [], 'Product count')
            ->addColumn('data_prod', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Product date')
            ->setComment('Warehouse Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();

    }
}
