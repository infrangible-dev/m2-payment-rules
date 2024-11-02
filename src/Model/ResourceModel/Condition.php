<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Condition extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'payment_condition',
            'id'
        );
    }
}
