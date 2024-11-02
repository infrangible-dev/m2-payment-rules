<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Condition;

use Infrangible\PaymentRules\Traits\Condition;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Delete extends \Infrangible\BackendWidget\Controller\Backend\Object\Delete
{
    use Condition;

    protected function getObjectNotFoundMessage(): string
    {
        return __('Unable to find the condition with id: %s!')->render();
    }

    protected function getObjectDeletedMessage(): string
    {
        return __('The condition has been deleted.')->render();
    }

    protected function getIndexUrlParams(): array
    {
        return ['id' => $this->getRequest()->getParam('rule_id')];
    }
}
