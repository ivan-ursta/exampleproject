<?php

namespace Perspective\Holidays\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class AddHolidayAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * Constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Apply patch to add the attribute.
     *
     * @return void
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            'holiday_product',
            [
                'type' => 'int',
                'label' => 'Святковий',
                'input' => 'boolean',
                'required' => false,
                'user_defined' => true,
                'backend' => '',
                'default' => 0, // Default value: No
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'group' => 'General',
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Get aliases (if any).
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Get dependencies (if any).
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }
}
