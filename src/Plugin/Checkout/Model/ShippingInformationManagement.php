<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Plugin\Checkout\Model;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\Session;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class ShippingInformationManagement
{
    /** @var Session */
    protected $checkoutSession;

    public function __construct(Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $extensionAttributes = $addressInformation->getExtensionAttributes();

        $validatedEmail = $extensionAttributes->getValidatedEmail();

        /** @noinspection PhpUndefinedMethodInspection */
        $this->checkoutSession->setValidatedEmail($validatedEmail);
    }
}
