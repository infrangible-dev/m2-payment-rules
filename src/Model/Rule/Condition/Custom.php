<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\Rule\Condition;

use Magento\Framework\DataObject;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
abstract class Custom
{
    abstract public function validateCustomer(DataObject $customer): bool;
}
