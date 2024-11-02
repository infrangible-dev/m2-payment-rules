<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PaymentRules\Block\Adminhtml\Condition;

use FeWeDev\Base\Arrays;
use Infrangible\PaymentRules\Model\Config\Source\Custom;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Form extends \Infrangible\BackendWidget\Block\Form
{
    /** @var Custom */
    protected $sourceCustom;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Arrays $arrays,
        \Infrangible\Core\Helper\Registry $registryHelper,
        \Infrangible\BackendWidget\Helper\Form $formHelper,
        Custom $sourceCustom,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $arrays,
            $registryHelper,
            $formHelper,
            $data
        );

        $this->sourceCustom = $sourceCustom;
    }

    protected function prepareFields(\Magento\Framework\Data\Form $form): void
    {
        $fieldSet = $form->addFieldset(
            'General',
            [
                'legend' => __('General')
            ]
        );

        $this->addCustomerAttributeCodeField(
            $fieldSet,
            'attribute_code',
            __('Customer Attribute')->render()
        );
        $this->addAddressAttributeCodeField(
            $fieldSet,
            'address_attribute_code',
            __('Address Attribute')->render()
        );
        $this->addOperatorField(
            $fieldSet,
            'operator',
            __('Operator')->render()
        );
        $this->addTextField(
            $fieldSet,
            'value',
            __('Value')->render()
        );
        $this->addOptionsField(
            $fieldSet,
            'custom_attribute',
            __('Custom Attribute')->render(),
            $this->sourceCustom->toOptionArray(),
            null
        );
    }
}
