<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Rule;

use Infrangible\PaymentRules\Traits\Rule;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Save extends \Infrangible\BackendWidget\Controller\Backend\Object\Save
{
    use Rule;

    protected function getObjectCreatedMessage(): string
    {
        return __('The rule has been created.')->render();
    }

    protected function getObjectUpdatedMessage(): string
    {
        return __('The rule has been updated.')->render();
    }
}
