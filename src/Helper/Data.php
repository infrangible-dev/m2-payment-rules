<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PaymentRules\Helper;

use FeWeDev\Base\Arrays;
use FeWeDev\Base\Variables;
use Infrangible\PaymentRules\Model\Condition;
use Infrangible\PaymentRules\Model\Config\Custom;
use Infrangible\PaymentRules\Model\Config\Source\Type;
use Infrangible\PaymentRules\Model\ResourceModel\Rule\CollectionFactory;
use Infrangible\PaymentRules\Model\Rule;
use Infrangible\PaymentRules\Model\Rule\ConditionFactory;
use Magento\Checkout\Model\Session;
use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Model\ResourceModel\Attribute\Collection;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\UniversalFactory;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Model\Quote;
use Psr\Log\LoggerInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Data
{
    /** key used to store and load rule */
    public const REGISTRY_KEY_RULE = 'current_rule';

    /** @var Variables */
    protected $variables;

    /** @var Arrays */
    protected $arrays;

    /** @var LoggerInterface */
    protected $logging;

    /** @var CollectionFactory */
    protected $ruleCollectionFactory;

    /** @var Custom */
    protected $config;

    /** @var ConditionFactory */
    protected $ruleConditionFactory;

    /** @var UniversalFactory */
    protected $universalFactory;

    /** @var Session */
    protected $checkoutSession;

    /** @var Collection */
    protected $customerAttributeCollection;

    /** @var \Magento\Customer\Model\ResourceModel\Address\Attribute\Collection */
    protected $addressAttributeCollection;

    public function __construct(
        Variables $variables,
        Arrays $arrays,
        LoggerInterface $logging,
        Custom $config,
        CollectionFactory $ruleCollectionFactory,
        ConditionFactory $ruleConditionFactory,
        UniversalFactory $universalFactory,
        Session $checkoutSession,
        \Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory $customerAttributeCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Address\Attribute\CollectionFactory $addressAttributeCollectionFactory
    ) {
        $this->variables = $variables;
        $this->arrays = $arrays;
        $this->logging = $logging;
        $this->config = $config;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->ruleConditionFactory = $ruleConditionFactory;
        $this->universalFactory = $universalFactory;
        $this->checkoutSession = $checkoutSession;
        $this->customerAttributeCollection = $customerAttributeCollectionFactory->create();
        $this->addressAttributeCollection = $addressAttributeCollectionFactory->create();
    }

    public function getCustomFieldData(): array
    {
        return $this->config->get();
    }

    /**
     * @param AbstractMethod|Adapter $methodInstance
     *
     * @throws \Exception
     */
    public function checkQuotePaymentMethod(
        Quote $quote,
        $methodInstance,
        DataObject $checkResult
    ): void {
        try {
            $this->checkQuotePaymentMethodCode(
                $quote,
                $methodInstance->getCode(),
                $checkResult
            );
        } catch (LocalizedException $exception) {
            $this->logging->error($exception);
        }
    }

    /**
     * @throws \Exception
     */
    public function checkQuotePaymentMethodCode(
        Quote $quote,
        string $paymentMethodCode,
        DataObject $checkResult
    ): void {
        if ($quote->getCustomerId()) {
            $customer = $quote->getCustomer();

            if ($customer instanceof Customer) {
                $customer = new DataObject($customer->__toArray());

                if ($customer->hasData('custom_attributes')) {
                    $customAttributes = $customer->getData('custom_attributes');

                    if (is_array($customAttributes)) {
                        foreach ($customAttributes as $attributeCode => $customAttribute) {
                            if (is_array($customAttribute) && array_key_exists(
                                    'value',
                                    $customAttribute
                                )) {
                                $customer->setData(
                                    $attributeCode,
                                    $customAttribute[ 'value' ]
                                );
                            }
                        }
                    }
                }
            }

            $this->addCustomerDataFromSourceObject(
                $customer,
                $quote->getBillingAddress(),
                ''
            );

            $this->checkPaymentMethod(
                $customer,
                $this->variables->intValue($quote->getStore()->getWebsiteId()),
                $paymentMethodCode,
                $checkResult
            );
        } else {
            $this->checkSourceObjectPaymentMethodCode(
                $quote->getBillingAddress(),
                '',
                $this->variables->intValue($quote->getStore()->getWebsiteId()),
                $paymentMethodCode,
                $checkResult
            );
        }
    }

    /**
     * @throws \Exception
     */
    public function checkSourceObjectPaymentMethodCode(
        DataObject $sourceObject,
        string $dataPrefix,
        int $websiteId,
        string $paymentMethodCode,
        DataObject $checkResult
    ): void {
        $customer = new DataObject();

        $this->addCustomerDataFromSourceObject(
            $customer,
            $sourceObject,
            $dataPrefix
        );

        $this->checkPaymentMethod(
            $customer,
            $websiteId,
            $paymentMethodCode,
            $checkResult
        );
    }

    protected function addCustomerDataFromSourceObject(
        DataObject $customer,
        DataObject $sourceObject,
        string $dataPrefix
    ): void {
        foreach ($this->customerAttributeCollection as $customerAttribute) {
            /** @var Attribute $customerAttribute */
            $attributeCode = $customerAttribute->getAttributeCode();

            $quoteDataField = sprintf(
                '%s%s',
                $dataPrefix,
                $attributeCode
            );

            $customer->setData(
                $attributeCode,
                $this->arrays->getValue(
                    $sourceObject->getData(),
                    $quoteDataField,
                    $this->arrays->getValue(
                        $customer->getData(),
                        $attributeCode
                    )
                )
            );
        }

        foreach ($this->addressAttributeCollection as $addressAttribute) {
            /** @var Attribute $addressAttribute */
            $attributeCode = $addressAttribute->getAttributeCode();

            $quoteDataField = sprintf(
                '%s%s',
                $dataPrefix,
                $attributeCode
            );

            $customer->setData(
                $attributeCode,
                $this->arrays->getValue(
                    $sourceObject->getData(),
                    $quoteDataField,
                    $this->arrays->getValue(
                        $customer->getData(),
                        $attributeCode
                    )
                )
            );
        }

        if ($sourceObject instanceof Quote\Address) {
            $validatedEmail = $this->checkoutSession->getData('validated_email');

            if (! $this->variables->isEmpty($validatedEmail)) {
                $customer->setData(
                    'email',
                    $validatedEmail
                );
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function checkPaymentMethod(
        DataObject $customer,
        int $websiteId,
        string $paymentMethodCode,
        DataObject $checkResult
    ): void {
        $restrictionRules = $this->getRules(
            $websiteId,
            $paymentMethodCode,
            Type::TYPE_RESTRICTION
        );

        $permissionRules = $this->getRules(
            $websiteId,
            $paymentMethodCode,
            Type::TYPE_PERMISSION
        );

        if ($this->variables->isEmpty($restrictionRules) && $this->variables->isEmpty($permissionRules)) {
            return;
        }

        if ($this->variables->isEmpty($restrictionRules)) {
            $checkResult->setData(
                'is_available',
                false
            );
        } else {
            foreach ($restrictionRules as $rule) {
                if (! array_key_exists(
                    'type',
                    $rule
                )) {
                    $this->logging->error('Missing type in rule data');
                    continue;
                }

                $type = $rule[ 'type' ];

                if ($this->variables->isEmpty($type)) {
                    $this->logging->error('Invalid type in rule data');
                    continue;
                }

                if (! array_key_exists(
                    'conditions',
                    $rule
                )) {
                    $this->logging->error(
                        sprintf(
                            'Missing conditions in rule for payment method with code: %s',
                            $paymentMethodCode
                        )
                    );
                    continue;
                }

                $conditions = $rule[ 'conditions' ];

                if ($this->variables->isEmpty($conditions)) {
                    $this->logging->error(
                        sprintf(
                            'Missing conditions in rule for payment method with code: %s',
                            $paymentMethodCode
                        )
                    );
                    continue;
                }

                $isRuleValid = true;

                foreach ($conditions as $condition) {
                    if ($condition instanceof Rule\Condition) {
                        $isRuleValid = $isRuleValid && $condition->validateCustomer($customer);
                    } elseif ($condition instanceof Rule\Condition\Custom) {
                        $isRuleValid = $isRuleValid && $condition->validateCustomer($customer);
                    }
                }

                if ($isRuleValid) {
                    $checkResult->setData(
                        'is_available',
                        false
                    );
                }
            }
        }

        if (! $checkResult->getData('is_available') && ! $this->variables->isEmpty($permissionRules)) {
            foreach ($permissionRules as $rule) {
                if (! array_key_exists(
                    'type',
                    $rule
                )) {
                    $this->logging->error('Missing type in rule data');
                    continue;
                }

                $type = $rule[ 'type' ];

                if ($this->variables->isEmpty($type)) {
                    $this->logging->error('Invalid type in rule data');
                    continue;
                }

                if (! array_key_exists(
                    'conditions',
                    $rule
                )) {
                    $this->logging->error(
                        sprintf(
                            'Missing conditions in rule for payment method with code: %s',
                            $paymentMethodCode
                        )
                    );
                    continue;
                }

                $conditions = $rule[ 'conditions' ];

                if ($this->variables->isEmpty($conditions)) {
                    $this->logging->error(
                        sprintf(
                            'Missing conditions in rule for payment method with code: %s',
                            $paymentMethodCode
                        )
                    );
                    continue;
                }

                $isRuleValid = true;

                foreach ($conditions as $condition) {
                    if ($condition instanceof Rule\Condition) {
                        $isRuleValid = $isRuleValid && $condition->validateCustomer($customer);
                    } elseif ($condition instanceof Rule\Condition\Custom) {
                        $isRuleValid = $isRuleValid && $condition->validateCustomer($customer);
                    }
                }

                if ($isRuleValid) {
                    $checkResult->setData(
                        'is_available',
                        true
                    );
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function getRules(int $websiteId, string $paymentMethodCode, int $type): array
    {
        $customFieldData = $this->getCustomFieldData();

        $rulesCollection = $this->ruleCollectionFactory->create();

        $rulesCollection->addWebsiteFilter($websiteId);
        $rulesCollection->addPaymentMethodCode($paymentMethodCode);
        $rulesCollection->addTypeFilter($type);
        $rulesCollection->addActiveFilter();

        $rules = [];

        /** @var Rule $rule */
        foreach ($rulesCollection as $rule) {
            $ruleData = [
                'type'       => $rule->getType(),
                'conditions' => []
            ];

            /** @var Condition $condition */
            foreach ($rule->getConditions() as $condition) {
                $attributeCode = $condition->getAttributeCode();
                $addressAttributeCode = $condition->getAddressAttributeCode();
                $customAttribute = $condition->getCustomAttribute();

                if (empty($customAttribute)) {
                    $ruleCondition = $this->ruleConditionFactory->create();

                    $ruleCondition->setAttribute(empty($attributeCode) ? $addressAttributeCode : $attributeCode);
                    $ruleCondition->setOperator($condition->getOperator());
                    $ruleCondition->setValue($condition->getValue());

                    $ruleData[ 'conditions' ][] = $ruleCondition;
                } else {
                    if (array_key_exists(
                        $customAttribute,
                        $customFieldData
                    )) {
                        $data = $customFieldData[ $customAttribute ];

                        if (is_array($data) && array_key_exists(
                                'model',
                                $data
                            )) {
                            $customCondition = $this->universalFactory->create($data[ 'model' ]);

                            if ($customCondition instanceof Rule\Condition\Custom) {
                                $ruleData[ 'conditions' ][] = $customCondition;
                            }
                        }
                    }
                }
            }

            $rules[] = $ruleData;
        }

        return $rules;
    }
}
