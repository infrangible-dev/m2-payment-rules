<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Block\Adminhtml\Widget\Grid\Column\Renderer;

use Exception;
use Infrangible\Core\Helper\Attribute;
use Infrangible\PaymentRules\Helper\Data;
use Infrangible\PaymentRules\Model\Condition;
use Infrangible\PaymentRules\Model\Rule;
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Psr\Log\LoggerInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Conditions extends AbstractRenderer
{
    /** @var Attribute */
    protected $eavAttributeHelper;

    /** @var Data */
    protected $paymentRulesHelper;

    /** @var LoggerInterface */
    protected $logging;

    public function __construct(
        Context $context,
        Attribute $eavAttributeHelper,
        Data $paymentRulesHelper,
        LoggerInterface $logging,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );

        $this->eavAttributeHelper = $eavAttributeHelper;
        $this->paymentRulesHelper = $paymentRulesHelper;

        $this->logging = $logging;
    }

    /**
     * @throws Exception
     */
    public function render(DataObject $row): string
    {
        $customFieldData = $this->paymentRulesHelper->getCustomFieldData();

        /** @var Rule $row */
        $conditions = $row->getConditions();

        $conditionsOutput = [];

        /** @var Condition $condition */
        foreach ($conditions as $condition) {
            $attributeCode = $condition->getAttributeCode();
            $addressAttributeCode = $condition->getAddressAttributeCode();
            $customAttribute = $condition->getCustomAttribute();

            if (empty($customAttribute)) {
                try {
                    if (empty($addressAttributeCode)) {
                        $attribute = $this->eavAttributeHelper->getAttribute(
                            'customer',
                            $attributeCode
                        );

                        $conditionsOutput[] = sprintf(
                            '<a href="%s">%s %s %s</a>',
                            $this->getUrl(
                                '*/condition/edit',
                                ['rule_id' => $row->getId(), 'id' => $condition->getId()]
                            ),
                            sprintf(
                                '%s (%s)',
                                $attribute->getData('frontend_label'),
                                $attributeCode
                            ),
                            $condition->getOperator(),
                            $condition->getValue()
                        );
                    } else {
                        $attribute = $this->eavAttributeHelper->getAttribute(
                            'customer_address',
                            $addressAttributeCode
                        );

                        $conditionsOutput[] = sprintf(
                            '<a href="%s">%s %s %s</a>',
                            $this->getUrl(
                                '*/condition/edit',
                                ['rule_id' => $row->getId(), 'id' => $condition->getId()]
                            ),
                            sprintf(
                                '%s (%s)',
                                $attribute->getData('frontend_label'),
                                $addressAttributeCode
                            ),
                            $condition->getOperator(),
                            $condition->getValue()
                        );
                    }
                } catch (Exception $exception) {
                    $this->logging->error($exception);
                }
            } else {
                if (array_key_exists(
                    $customAttribute,
                    $customFieldData
                )) {
                    $data = $customFieldData[ $customAttribute ];

                    if (is_array($data) && array_key_exists(
                            'label',
                            $data
                        )) {
                        $conditionsOutput[] = sprintf(
                            '<a href="%s">%s %s %s</a>',
                            $this->getUrl(
                                '*/condition/edit',
                                ['rule_id' => $row->getId(), 'id' => $condition->getId()]
                            ),
                            $data[ 'label' ],
                            $condition->getOperator(),
                            $condition->getValue()
                        );
                    }
                }
            }
        }

        return sprintf(
            '<div style="float: right;">&raquo; <a href="%s">%s</a></div><div>%s</div>',
            $this->getUrl(
                '*/condition/add',
                ['rule_id' => $row->getId()]
            ),
            __('Add'),
            implode(
                '<br />',
                $conditionsOutput
            )
        );
    }
}
