<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\Config\Custom;

use Magento\Framework\Config\Dom;
use Magento\Framework\Config\FileResolverInterface;
use Magento\Framework\Config\Reader\Filesystem;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Reader extends Filesystem
{
    public function __construct(
        FileResolverInterface $fileResolver,
        Converter $converter,
        SchemaLocator $schemaLocator,
        ValidationState $validationState,
        array $idAttributes = [],
        string $domDocumentClass = Dom::class,
        string $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            'payment_rules_attribute.xml',
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
