<?php

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model\Config\Custom;

use DOMDocument;
use DOMNode;
use Magento\Framework\Config\ConverterInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Converter implements ConverterInterface
{
    /**
     * Convert config
     *
     * @param DOMDocument $source
     */
    public function convert($source): array
    {
        $result = [];

        /** @var DOMNode $childNode */
        foreach ($source->childNodes as $childNode) {
            if ($childNode->nodeName === 'config') {
                /** @var DOMNode $childChildNode */
                foreach ($childNode->childNodes as $childChildNode) {
                    $name = null;
                    $label = null;
                    $model = null;

                    if ($childChildNode->nodeName === 'custom_field') {
                        $attributes = $childChildNode->attributes;

                        if ($attributes) {
                            $nameNode = $attributes->getNamedItem('name');

                            if ($nameNode) {
                                $name = (string)$nameNode->nodeValue;
                            }

                            /** @var DOMNode $childChildChildNode */
                            foreach ($childChildNode->childNodes as $childChildChildNode) {
                                if ($childChildChildNode->nodeName === 'label') {
                                    $label = (string)$childChildChildNode->nodeValue;
                                }
                                if ($childChildChildNode->nodeName === 'model') {
                                    $model = (string)$childChildChildNode->nodeValue;
                                }
                            }
                        }
                    }

                    if (! empty($name) && ! empty($label) && ! empty($model)) {
                        $result[ $name ] = ['label' => $label, 'model' => $model];
                    }
                }
            }
        }

        return $result;
    }
}
