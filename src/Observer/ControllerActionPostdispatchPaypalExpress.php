<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Observer;

use Infrangible\PaymentRules\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Paypal\Controller\Express\AbstractExpress;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class ControllerActionPostdispatchPaypalExpress implements ObserverInterface
{
    /** @var Data */
    protected $paymentRulesHelper;

    /** @var Session */
    protected $checkoutSession;

    /** @var MessageManagerInterface */
    protected $messageManager;

    /** @var RedirectInterface */
    protected $redirect;

    public function __construct(Data $paymentRulesHelper, Session $checkoutSession, Context $context)
    {
        $this->paymentRulesHelper = $paymentRulesHelper;
        $this->checkoutSession = $checkoutSession;
        $this->messageManager = $context->getMessageManager();
        $this->redirect = $context->getRedirect();
    }

    /**
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    public function execute(Observer $observer): void
    {
        /** @var AbstractExpress $action */
        $action = $observer->getEvent()->getData('controller_action');

        $quote = $this->checkoutSession->getQuote();

        if ($quote === null) {
            return;
        }

        $checkResult = new DataObject();

        $checkResult->setData(
            'is_available',
            true
        );

        $this->paymentRulesHelper->checkSourceObjectPaymentMethodCode(
            $quote->getShippingAddress(),
            '',
            $quote->getStore()->getWebsiteId(),
            'paypal_express',
            $checkResult
        );

        if (! $checkResult->getData('is_available')) {
            $this->messageManager->addErrorMessage(__('This payment method is not allowed.'));

            $response = $action->getResponse();

            if ($response instanceof Http) {
                $this->redirect->redirect(
                    $response,
                    'checkout/cart'
                );
            }
        }
    }
}
