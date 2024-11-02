<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PaymentRules\Block\Adminhtml\Condition;

use Exception;
use FeWeDev\Base\Arrays;
use FeWeDev\Base\Variables;
use Infrangible\BackendWidget\Helper\Session;
use Infrangible\Core\Helper\Database;
use Infrangible\Core\Helper\Registry;
use Infrangible\PaymentRules\Model\Config\Source\Custom;
use Infrangible\PaymentRules\Model\ResourceModel\Condition\Collection;
use Infrangible\PaymentRules\Model\Rule;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Eav\Model\Config;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Validator\UniversalFactory;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Grid extends \Infrangible\BackendWidget\Block\Grid
{
    /** @var Custom */
    protected $sourceCustom;

    public function __construct(
        Context $context,
        Data $backendHelper,
        Database $databaseHelper,
        Arrays $arrays,
        Variables $variables,
        Registry $registryHelper,
        \Infrangible\BackendWidget\Helper\Grid $gridHelper,
        Session $sessionHelper,
        UniversalFactory $universalFactory,
        Config $eavConfig,
        Custom $sourceCustom,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $backendHelper,
            $databaseHelper,
            $arrays,
            $variables,
            $registryHelper,
            $gridHelper,
            $sessionHelper,
            $universalFactory,
            $eavConfig,
            $data
        );

        $this->sourceCustom = $sourceCustom;
    }

    /**
     * @throws Exception
     */
    protected function prepareCollection(AbstractDb $collection): void
    {
        /** @var Rule $rule */
        $rule = $this->registryHelper->registry(\Infrangible\PaymentRules\Helper\Data::REGISTRY_KEY_RULE);

        /** @var Collection $collection */
        $collection->filterByRule($this->variables->intValue($rule->getId()));

        $this->setCollection($collection);
    }

    /**
     * @throws Exception
     */
    protected function prepareFields(): void
    {
        $this->addCustomerAttributeCodeColumn(
            'attribute_code',
            __('Customer Attribute')->render()
        );
        $this->addAddressAttributeCodeColumn(
            'address_attribute_code',
            __('Address Attribute')->render()
        );
        $this->addOperatorColumn(
            'operator',
            __('Operator')->render()
        );
        $this->addTextColumn(
            'value',
            __('Value')->render()
        );
        $this->addOptionsColumn(
            'custom_attribute',
            __('Custom Attribute')->render(),
            $this->sourceCustom->toOptions()
        );
    }

    /**
     * @return string[]
     */
    protected function getHiddenFieldNames(): array
    {
        return [];
    }
}
