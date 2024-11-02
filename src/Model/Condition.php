<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @method string getRuleId()
 * @method void setRuleId(string $ruleId)
 * @method string getAttributeCode()
 * @method void setAttributeCode(string $attributeCode)
 * @method string getAddressAttributeCode()
 * @method void setAddressAttributeCode(string $addressAttributeCode)
 * @method string getOperator()
 * @method void setOperator(string $operator)
 * @method string getValue()
 * @method void setValue(string $value)
 * @method string getCustomAttribute()
 * @method void setCustomAttribute(string $id)
 */
class Condition extends AbstractModel
{
    protected function _construct(): void
    {
        parent::_construct();

        $this->_init(ResourceModel\Condition::class);
    }

    public function beforeSave(): AbstractModel
    {
        parent::beforeSave();

        $customAttribute = $this->getData('custom_attribute');

        if (! empty($customAttribute)) {
            $this->setData('attribute_code');
            $this->setData('operator');
            $this->setData('value');
        }

        return $this;
    }
}
