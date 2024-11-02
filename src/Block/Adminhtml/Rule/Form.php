<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PaymentRules\Block\Adminhtml\Rule;

use FeWeDev\Base\Arrays;
use Infrangible\PaymentRules\Helper\Adminhtml;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Form extends \Infrangible\BackendWidget\Block\Form
{
    /** @var Adminhtml */
    protected $paymentRulesAdminhtmlHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Arrays $arrays,
        \Infrangible\Core\Helper\Registry $registryHelper,
        \Infrangible\BackendWidget\Helper\Form $formHelper,
        Adminhtml $paymentRulesAdminhtmlHelper,
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

        $this->paymentRulesAdminhtmlHelper = $paymentRulesAdminhtmlHelper;
    }

    /**
     * @throws LocalizedException
     */
    protected function prepareFields(\Magento\Framework\Data\Form $form): void
    {
        $fieldSet = $form->addFieldset(
            'General',
            [
                'legend' => __('General')
            ]
        );

        $this->addWebsiteSelectField(
            $fieldSet,
            'website_id'
        );
        $this->addPaymentActiveMethodsField(
            $fieldSet,
            'payment_method_code',
            null,
            false,
            false,
            false,
            true
        );
        $this->paymentRulesAdminhtmlHelper->addTypeField(
            $fieldSet,
            'type',
            __('Type')->render(),
            $this->getObject()
        );
        $this->addYesNoField(
            $fieldSet,
            'active',
            __('Active')->render()
        );
    }
}
