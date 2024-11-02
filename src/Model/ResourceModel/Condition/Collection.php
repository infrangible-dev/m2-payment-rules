<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\ResourceModel\Condition;

use Infrangible\PaymentRules\Model\Condition;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        parent::_construct();

        $this->_init(
            Condition::class,
            \Infrangible\PaymentRules\Model\ResourceModel\Condition::class
        );
    }

    public function filterByRule(int $ruleId): void
    {
        $this->addFieldToFilter(
            'rule_id',
            $ruleId
        );
    }
}
