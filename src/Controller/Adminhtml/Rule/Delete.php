<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Rule;

use Infrangible\PaymentRules\Traits\Rule;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Delete extends \Infrangible\BackendWidget\Controller\Backend\Object\Delete
{
    use Rule;

    protected function getObjectDeletedMessage(): string
    {
        return __('The rule has been deleted.')->render();
    }
}
