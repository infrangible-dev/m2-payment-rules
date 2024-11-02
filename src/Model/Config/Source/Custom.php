<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\Config\Source;

use Infrangible\PaymentRules\Helper\Data;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Custom
{
    /** @var Data */
    protected $paymentRulesHelper;

    public function __construct(Data $paymentRulesHelper)
    {
        $this->paymentRulesHelper = $paymentRulesHelper;
    }

    public function toOptionArray(): array
    {
        $options = [['value' => '', 'label' => __('--Please Select--')]];

        foreach ($this->paymentRulesHelper->getCustomFieldData() as $value => $data) {
            if (is_array($data) && array_key_exists(
                    'label',
                    $data
                )) {
                $options[] = ['value' => $value, 'label' => $data[ 'label' ]];
            }
        }

        return $options;
    }

    public function toOptions(): array
    {
        $options = [];

        foreach ($this->paymentRulesHelper->getCustomFieldData() as $value => $data) {
            if (is_array($data) && array_key_exists(
                    'label',
                    $data
                )) {
                $options[ $value ] = $data[ 'label' ];
            }
        }

        return $options;
    }
}
