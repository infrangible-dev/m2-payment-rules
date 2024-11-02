<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Condition;

use Infrangible\PaymentRules\Traits\Condition;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class MassDelete extends \Infrangible\BackendWidget\Controller\Backend\Object\MassDelete
{
    use Condition;

    protected function getObjectsDeletedMessage(): string
    {
        return __('%d condition(s) have been deleted.')->render();
    }

    protected function getIndexUrlParams(): array
    {
        return ['id' => $this->getRequest()->getParam('rule_id')];
    }
}
