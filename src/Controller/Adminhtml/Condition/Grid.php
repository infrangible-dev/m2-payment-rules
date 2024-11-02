<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Condition;

use Infrangible\PaymentRules\Traits\Condition;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Grid extends \Infrangible\BackendWidget\Controller\Backend\Object\Grid
{
    use Condition;

    protected function getGridUrlParams(): array
    {
        return ['id' => $this->getRequest()->getParam('id')];
    }

    protected function getEditUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('id')];
    }

    protected function getDeleteUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('id')];
    }

    protected function getMassDeleteUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('id')];
    }
}
