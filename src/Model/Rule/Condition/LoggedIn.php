<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\Rule\Condition;

use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class LoggedIn extends Custom
{
    /** @var Session */
    protected $customerSession;

    public function __construct(Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    public function validateCustomer(DataObject $customer): bool
    {
        return $this->customerSession->isLoggedIn();
    }
}
