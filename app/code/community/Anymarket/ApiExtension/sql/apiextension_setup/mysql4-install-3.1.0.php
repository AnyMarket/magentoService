<?php
$this->startSetup();
$tableName = $this->getTable('apiextension/anymarketfeed');
if( $this->getConnection()->isTableExists( $tableName ) != true ) {
    $table = $this->getConnection()
        ->newTable($tableName)
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'AnyMarket Feed ID'
        )
        ->addColumn(
            'type',
            Varien_Db_Ddl_Table::TYPE_TEXT, 255,
            array(
                'nullable' => false,
            ),
            'Type of feed'
        )
        ->addColumn(
            'id_item',
            Varien_Db_Ddl_Table::TYPE_TEXT, 255,
            array(
                'nullable' => false,
            ),
            'Id item in Feed'
        )
        ->addColumn(
            'oi',
            Varien_Db_Ddl_Table::TYPE_TEXT, 255,
            array(
                'nullable' => false,
            ),
            'OI Anymarket'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(),
            'AnyMarket Feed Modification Time'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(),
            'AnyMarket Feed Creation Time'
        )
        ->setComment('AnyMarket Feed Table');
    $this->getConnection()->createTable($table);
}
$tableName = $this->getTable('apiextension/anymarketorder');
if( $this->getConnection()->isTableExists( $tableName ) != true ) {
    $table = $this->getConnection()
        ->newTable($tableName)
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'Anymarket Order ID'
        )
        ->addColumn(
            'id_anymarket',
            Varien_Db_Ddl_Table::TYPE_TEXT, 255,
            array(
                'nullable' => false,
            ),
            'ID Order in Anymarket'
        )
        ->addColumn(
            'id_magento',
            Varien_Db_Ddl_Table::TYPE_TEXT, 255,
            array(
                'nullable' => false,
            ),
            'ID Order in Magento'
        )
        ->addColumn(
            'oi',
            Varien_Db_Ddl_Table::TYPE_TEXT, 255,
            array(
                'nullable' => false,
            ),
            'OI Anymarket'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(),
            'AnyMarket Order Modification Time'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(),
            'AnyMarket Order Creation Time'
        )
        ->setComment('Anymarket Order Table');
    $this->getConnection()->createTable($table);
}

$this->endSetup();