<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\PaymentRules\Model;

use FeWeDev\Base\Variables;
use Infrangible\PaymentRules\Model\ResourceModel\Condition\Collection;
use Infrangible\PaymentRules\Model\ResourceModel\Condition\CollectionFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @method int getWebsiteId()
 * @method void setWebsiteId(string $websiteId)
 * @method int getPaymentMethodCode()
 * @method void setPaymentMethodCode(string $paymentMethodCode)
 * @method int getType()
 * @method void setType(int $type)
 * @method int getActive()
 * @method void setActive(int $active)
 * @method string getCreatedAt()
 * @method void setCreatedAt(string $createdAt)
 * @method string getUpdatedAt()
 * @method void setUpdatedAt(string $updatedAt)
 */
class Rule extends AbstractModel
{
    /** @var CollectionFactory */
    protected $conditionCollectionFactory;

    /** @var Variables */
    protected $variables;

    public function __construct(
        Context $context,
        Registry $registry,
        CollectionFactory $conditionCollectionFactory,
        Variables $variables,
        ?AbstractResource $resource = null,
        ?AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );

        $this->conditionCollectionFactory = $conditionCollectionFactory;
        $this->variables = $variables;
    }

    protected function _construct(): void
    {
        parent::_construct();

        $this->_init(ResourceModel\Rule::class);
    }

    public function beforeSave(): AbstractModel
    {
        parent::beforeSave();

        if ($this->isObjectNew()) {
            $this->setCreatedAt(gmdate('Y-m-d'));
        }

        $this->setUpdatedAt(gmdate('Y-m-d'));

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function getConditions(): Collection
    {
        $collection = $this->conditionCollectionFactory->create();

        $collection->filterByRule($this->variables->intValue($this->getId()));

        return $collection;
    }
}
