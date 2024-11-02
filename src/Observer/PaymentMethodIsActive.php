<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PaymentRules\Observer;

use Infrangible\PaymentRules\Helper\Data;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Model\Quote;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class PaymentMethodIsActive implements ObserverInterface
{
    /** @var Data */
    protected $paymentRulesHelper;

    public function __construct(Data $paymentRulesHelper)
    {
        $this->paymentRulesHelper = $paymentRulesHelper;
    }

    public function execute(Observer $observer): void
    {
        /** @var DataObject $checkResult */
        $checkResult = $observer->getEvent()->getData('result');

        if ($checkResult === null || ! $checkResult->getData('is_available')) {
            return;
        }

        /** @var Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        if ($quote === null) {
            return;
        }

        /** @var AbstractMethod|Adapter $methodInstance */
        $methodInstance = $observer->getEvent()->getData('method_instance');

        if ($methodInstance === null) {
            return;
        }

        $this->paymentRulesHelper->checkQuotePaymentMethod(
            $quote,
            $methodInstance,
            $checkResult
        );
    }
}
