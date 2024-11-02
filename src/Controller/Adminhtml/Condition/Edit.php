<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Condition;

use Infrangible\PaymentRules\Traits\Condition;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Edit extends \Infrangible\BackendWidget\Controller\Backend\Object\Edit
{
    use Condition;

    protected function getIndexUrlParams(): array
    {
        return ['id' => $this->getRequest()->getParam('rule_id')];
    }

    protected function getDeleteUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('rule_id')];
    }

    protected function getSaveUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('rule_id')];
    }
}
