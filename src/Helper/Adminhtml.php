<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PaymentRules\Helper;

use Exception;
use Infrangible\BackendWidget\Helper\Form;
use Infrangible\BackendWidget\Helper\Grid;
use Infrangible\PaymentRules\Model\Config\Source\Type;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Framework\Data\Form\Element\Fieldset;
use Magento\Framework\Model\AbstractModel;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Adminhtml
{
    /** @var Grid */
    protected $gridHelper;

    /** @var Form */
    protected $formHelper;

    /** @var Type */
    protected $sourceType;

    public function __construct(Grid $gridHelper, Form $formHelper, Type $sourceType)
    {
        $this->gridHelper = $gridHelper;
        $this->formHelper = $formHelper;
        $this->sourceType = $sourceType;
    }

    /**
     * @throws Exception
     */
    public function addTypeColumn(Extended $grid, string $objectFieldName, string $label): void
    {
        $this->gridHelper->addOptionsColumn(
            $grid,
            $objectFieldName,
            $label,
            $this->sourceType->toOptions()
        );
    }

    public function addTypeField(
        Fieldset $fieldSet,
        string $objectFieldName,
        string $label,
        AbstractModel $object
    ): void {
        $this->formHelper->addOptionsField(
            $fieldSet,
            'current_rule',
            $objectFieldName,
            $label,
            $this->sourceType->toOptionArray(),
            1,
            $object,
            true
        );
    }
}
