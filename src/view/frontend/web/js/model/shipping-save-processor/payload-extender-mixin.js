// noinspection JSUnresolvedVariable,JSUnresolvedFunction

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/checkout-data'
], function (wrapper, checkoutData) {
    'use strict';

    return function (payload) {
        return wrapper.wrap(payload, function (originalAction, payload) {
            originalAction();

            payload.addressInformation['extension_attributes']['validated_email'] = checkoutData.getValidatedEmailValue();

            return payload;
        });
    };
});
