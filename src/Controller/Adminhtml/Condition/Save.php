<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Condition;

use Magento\Framework\Model\AbstractModel;
use Infrangible\PaymentRules\Traits\Condition;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Save
    extends \Infrangible\BackendWidget\Controller\Backend\Object\Save
{
    use Condition;

    protected function getObjectCreatedMessage(): string
    {
        return __('The condition has been created.')->render();
    }

    protected function getObjectUpdatedMessage(): string
    {
        return __('The condition has been updated.')->render();
    }

    protected function getIndexUrlParams(): array
    {
        return ['id' => $this->getRequest()->getParam('rule_id')];
    }

    protected function getAddUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('id')];
    }

    protected function getEditUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('id')];
    }

    protected function getDeleteUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('id')];
    }

    protected function beforeSave(AbstractModel $object): void
    {
        parent::beforeSave($object);

        $ruleId = $this->getRequest()->getParam('rule_id');

        $object->setData('rule_id', $ruleId);
    }
}
