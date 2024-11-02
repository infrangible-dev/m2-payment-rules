<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\Config\Source;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Type
{
    public const TYPE_RESTRICTION = 1;
    public const TYPE_PERMISSION = 2;

    public function toOptionArray(): array
    {
        return [
            ['value' => '', 'label' => __('--Please Select--')],
            ['value' => static::TYPE_RESTRICTION, 'label' => __('Restriction')],
            ['value' => static::TYPE_PERMISSION, 'label' => __('Permission')]
        ];
    }

    public function toOptions(): array
    {
        return [
            static::TYPE_RESTRICTION => __('Restriction'),
            static::TYPE_PERMISSION  => __('Permission')
        ];
    }
}
