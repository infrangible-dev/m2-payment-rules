<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\Rule;

use Magento\Framework\DataObject;
use Magento\Rule\Model\Condition\AbstractCondition;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @method getAttribute()
 * @method setAttribute(string $attribute)
 * @method setOperator(string $operator)
 * @method setValue(mixed $value)
 */
class Condition extends AbstractCondition
{
    public function validateCustomer(DataObject $customer): bool
    {
        return $this->validateAttribute($customer->getData($this->getAttribute()));
    }
}
