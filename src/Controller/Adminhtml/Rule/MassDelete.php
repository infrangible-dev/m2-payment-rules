<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Controller\Adminhtml\Rule;

use Infrangible\PaymentRules\Traits\Rule;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class MassDelete extends \Infrangible\BackendWidget\Controller\Backend\Object\MassDelete
{
    use Rule;

    protected function getObjectsDeletedMessage(): string
    {
        return __('%d rule(s) have been deleted.')->render();
    }
}