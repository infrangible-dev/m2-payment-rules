<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        $ruleTableName = $setup->getTable('payment_rule');
        $conditionTableName = $setup->getTable('payment_condition');

        $this->createRuleTable(
            $setup,
            $ruleTableName
        );

        $this->createConditionTable(
            $setup,
            $conditionTableName,
            $ruleTableName
        );

        $setup->endSetup();
    }

    /**
     * @throws Zend_Db_Exception
     */
    private function createRuleTable(SchemaSetupInterface $setup, string $ruleTableName): void
    {
        if ($setup->tableExists($ruleTableName)) {
            return;
        }

        $connection = $setup->getConnection();

        $ruleTable = $connection->newTable($ruleTableName);

        $ruleTable->addColumn(
            'id',
            Table::TYPE_INTEGER,
            10,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true
            ]
        );
        $ruleTable->addColumn(
            'website_id',
            Table::TYPE_SMALLINT,
            5,
            [
                'unsigned' => true,
                'nullable' => false,
                'default'  => '0'
            ]
        );
        $ruleTable->addColumn(
            'payment_method_code',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false]
        );
        $ruleTable->addColumn(
            'type',
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false, 'default' => 1]
        );
        $ruleTable->addColumn(
            'active',
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false, 'default' => 1]
        );
        $ruleTable->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default'  => '0000-00-00 00:00:00'
            ]
        );
        $ruleTable->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default'  => '0000-00-00 00:00:00'
            ]
        );

        $ruleTable->addForeignKey(
            $setup->getFkName(
                $ruleTableName,
                'website_id',
                $setup->getTable('store_website'),
                'website_id'
            ),
            'website_id',
            $setup->getTable('store_website'),
            'website_id',
            Table::ACTION_CASCADE
        );
        $ruleTable->addIndex(
            'payment_rules',
            ['website_id', 'payment_method_code', 'type', 'active'],
            ['type' => AdapterInterface::INDEX_TYPE_INDEX]
        );

        $connection->createTable($ruleTable);
    }

    /**
     * @throws Zend_Db_Exception
     */
    private function createConditionTable(
        SchemaSetupInterface $setup,
        string $conditionTableName,
        string $ruleTableName
    ): void {
        if ($setup->tableExists($conditionTableName)) {
            return;
        }

        $connection = $setup->getConnection();

        $conditionTable = $connection->newTable($conditionTableName);

        $conditionTable->addColumn(
            'id',
            Table::TYPE_INTEGER,
            10,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true
            ]
        );
        $conditionTable->addColumn(
            'rule_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false]
        );
        $conditionTable->addColumn(
            'attribute_code',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null]
        );
        $conditionTable->addColumn(
            'address_attribute_code',
            Table::TYPE_TEXT,
            255,
            [
                'nullable' => true,
                'default'  => null
            ]
        );
        $conditionTable->addColumn(
            'operator',
            Table::TYPE_TEXT,
            10,
            ['nullable' => true, 'default' => null]
        );
        $conditionTable->addColumn(
            'value',
            Table::TYPE_TEXT,
            2000,
            ['nullable' => true, 'default' => null]
        );
        $conditionTable->addColumn(
            'custom_attribute',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null]
        );

        $conditionTable->addForeignKey(
            $setup->getFkName(
                $conditionTableName,
                'rule_id',
                $ruleTableName,
                'id'
            ),
            'rule_id',
            $ruleTableName,
            'id',
            Table::ACTION_CASCADE
        );
        $conditionTable->addIndex(
            'rule_conditions',
            ['rule_id'],
            ['type' => AdapterInterface::INDEX_TYPE_INDEX]
        );

        $connection->createTable($conditionTable);
    }
}
