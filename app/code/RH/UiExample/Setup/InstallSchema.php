<?php

namespace RH\UiExample\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;

        $installer->startSetup();
        /**
         * Create table 'rh_blog'
         */
        if (!$installer->tableExists('rh_blog')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('rh_blog')
            )->addColumn(
                'blog_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'Blog ID'
            )->addColumn(
                'blog_title',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable => false',
                ],
                'Blog Title'
            )->addColumn(
                'blog_description',
                Table::TYPE_TEXT,
                '2M',
                [],
                'Blog Description'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                [
                    'nullable' => false,
                ],
                'Status'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => Table::TIMESTAMP_INIT,
                ],
                'Created At'
            )->setComment('Blog Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
