<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PaymentRules\Block\Adminhtml\Rule;

use Exception;
use FeWeDev\Base\Arrays;
use FeWeDev\Base\Variables;
use Infrangible\BackendWidget\Helper\Session;
use Infrangible\Core\Helper\Database;
use Infrangible\Core\Helper\Registry;
use Infrangible\PaymentRules\Block\Adminhtml\Widget\Grid\Column\Renderer\Conditions;
use Infrangible\PaymentRules\Helper\Adminhtml;
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
    /** @var Adminhtml */
    protected $paymentRulesAdminhtmlHelper;

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
        Adminhtml $paymentRulesAdminhtmlHelper,
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

        $this->paymentRulesAdminhtmlHelper = $paymentRulesAdminhtmlHelper;
    }

    protected function prepareCollection(AbstractDb $collection): void
    {
    }

    /**
     * @throws Exception
     */
    protected function prepareFields(): void
    {
        $this->addWebsiteNameColumn('website_id');
        $this->addPaymentActiveMethods(
            'payment_method_code',
            null,
            true
        );
        $this->paymentRulesAdminhtmlHelper->addTypeColumn(
            $this,
            'type',
            __('Type')->render()
        );
        $this->addTextColumnWithRenderer(
            'conditions',
            __('Conditions')->render(),
            Conditions::class
        );
        $this->addYesNoColumn(
            'active',
            __('Active')->render()
        );

        $this->addAction(
            'conditions',
            __('Conditions')->render(),
            '*/condition/index'
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
