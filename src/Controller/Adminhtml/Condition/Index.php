<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Condition;

use Infrangible\Core\Helper\Registry;
use Infrangible\PaymentRules\Helper\Data;
use Infrangible\PaymentRules\Model\RuleFactory;
use Infrangible\PaymentRules\Traits\Condition;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Index extends \Infrangible\BackendWidget\Controller\Backend\Object\Index
{
    use Condition;

    /** @var Registry */
    protected $registryHelper;

    /** @var RuleFactory */
    protected $ruleFactory;

    /** @var \Infrangible\PaymentRules\Model\ResourceModel\RuleFactory */
    protected $ruleResourceFactory;

    public function __construct(
        Registry $registryHelper,
        Context $context,
        RuleFactory $ruleFactory,
        \Infrangible\PaymentRules\Model\ResourceModel\RuleFactory $ruleResourceFactory
    ) {
        parent::__construct($context);

        $this->registryHelper = $registryHelper;

        $this->ruleFactory = $ruleFactory;
        $this->ruleResourceFactory = $ruleResourceFactory;
    }

    protected function getGridUrlParams(): array
    {
        return ['id' => $this->getRequest()->getParam('id')];
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

    protected function getMassDeleteUrlParams(): array
    {
        return ['rule_id' => $this->getRequest()->getParam('id')];
    }

    protected function getBackUrlRoute(): string
    {
        return '*/rule/index';
    }

    /**
     * @return Page|void
     */
    public function execute()
    {
        $ruleId = $this->getRequest()->getParam('id');

        $rule = $this->ruleFactory->create();

        $this->ruleResourceFactory->create()->load(
            $rule,
            $ruleId
        );

        if (! $rule->getId()) {
            $this->getMessageManager()->addErrorMessage(
                sprintf(
                    'Unable to find the rule with id: %s',
                    $ruleId
                )
            );

            $this->_redirect('*/rule/index');

            return;
        }

        $this->registryHelper->register(
            Data::REGISTRY_KEY_RULE,
            $rule
        );

        return parent::execute();
    }
}
