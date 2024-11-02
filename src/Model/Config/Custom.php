<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\Config;

use Infrangible\PaymentRules\Model\Config\Custom\Reader;
use Magento\Framework\Config\CacheInterface;
use Magento\Framework\Config\Data;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Custom extends Data
{
    public function __construct(
        Reader $reader,
        CacheInterface $cache,
        ?SerializerInterface $serializer = null
    ) {
        parent::__construct(
            $reader,
            $cache,
            'payment_rules_attribute',
            $serializer
        );
    }

}
