<?php

namespace Perspective\DbWareHouse\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{
	protected $_postFactory;

	public function __construct(\Perspective\DbWareHouse\Model\PostFactory $postFactory)
	{
		$this->_postFactory = $postFactory;
	}

	public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
		if (version_compare($context->getVersion(), '1.2.0', '<')) {
			$data = [
                [
                    'name_war' => "Warehouse 1",
                    'addr_war' => "вул. Лесі Українки 53",
                    'id_cat' => "23",
                    'id_prod' => "1380",
                    'kol_prod' => 150,
                    'data_prod' => "2023-01-01"
                ],
                [
                    'name_war' => "Warehouse 2",
                    'addr_war' => "вул. Шевченка 23",
                    'id_cat' => "18",
                    'id_prod' => "880",
                    'kol_prod' => 99,
                    'data_prod' => "2022-12-12"
                ],
                [
                    'name_war' => "Warehouse 3",
                    'addr_war' => "вул. Івана Франка 49",
                    'id_cat' => "17",
                    'id_prod' => "724",
                    'kol_prod' => 0,
                    'data_prod' => "2022-11-11"
                ],
                [
                    'name_war' => "Warehouse 2",
                    'addr_war' => "вул. Шевченка 23",
                    'id_cat' => "25",
                    'id_prod' => "1588",
                    'kol_prod' => 50,
                    'data_prod' => "2023-01-01"
                ],
                [
                    'name_war' => "Warehouse 3",
                    'addr_war' => "вул. Івана Франка 49",
                    'id_cat' => "16",
                    'id_prod' => "622",
                    'kol_prod' => 150,
                    'data_prod' => "2023-01-01"
                ],
                [
                    'name_war' => "Warehouse 1",
                    'addr_war' => "вул. Лесі Українки 53",
                    'id_cat' => "19",
                    'id_prod' => "1028",
                    'kol_prod' => 150,
                    'data_prod' => "2023-01-01"
                ],
                [
                    'name_war' => "Warehouse 1",
                    'addr_war' => "вул. Лесі Українки 53",
                    'id_cat' => "28",
                    'id_prod' => "2040",
                    'kol_prod' => 150,
                    'data_prod' => "2023-01-01"
                ],
            ];
            
            foreach($data as $curpost)
            {
                $post = $this->_postFactory->create();
                $post->addData($curpost)->save();
            }
			
		}
	}
}