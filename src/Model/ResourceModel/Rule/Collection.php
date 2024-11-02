<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\ResourceModel\Rule;

use Infrangible\PaymentRules\Model\Rule;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_init(
            Rule::class,
            \Infrangible\PaymentRules\Model\ResourceModel\Rule::class
        );
    }

    public function addWebsiteFilter(int $websiteId): void
    {
        $this->addFieldToFilter(
            'website_id',
            $websiteId
        );
    }

    public function addPaymentMethodCode(string $paymentMethodCode): void
    {
        $this->addFieldToFilter(
            'payment_method_code',
            $paymentMethodCode
        );
    }

    public function addTypeFilter(int $type): void
    {
        $this->addFieldToFilter(
            'type',
            $type
        );
    }

    public function addRestrictionTypeFilter(): void
    {
        $this->addFieldToFilter(
            'type',
            1
        );
    }

    public function addPermissionTypeFilter(): void
    {
        $this->addFieldToFilter(
            'type',
            2
        );
    }

    public function addActiveFilter(): void
    {
        $this->addFieldToFilter(
            'active',
            1
        );
    }
}
