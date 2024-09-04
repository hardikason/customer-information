<?php
namespace SK\CustomerInformation\Block\Account;

use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class Info extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * __construct
     *
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        OrderCollectionFactory $orderCollectionFactory,
        array $data = []
    ) {

        $this->customerSession = $customerSession;
        $this->orderCollectionFactory = $orderCollectionFactory;

        parent::__construct($context, $data);
    }

    /**
     * Generate cache key info. This should be unique for each cacheable block.
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            'CUSTOMER_INFO_',
            microtime()
        ];
    }

    /**
     * _prepareLayout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Customer Information'));

        if ($this->getOrders()->getSize()) {
            $pager = $this->getLayout()->createBlock(\Magento\Theme\Block\Html\Pager::class, 'customer.orders.pager')
                ->setShowPerPage(true)
                ->setCollection($this->getOrders());
            $this->setChild('pager', $pager);
        }

        return $this;
    }

    /**
     * Get Customer
     *
     * @return \Magento\Customer\Model\ResourceModel\Customer
     */
    public function getCustomer()
    {
        return $this->customerSession->getCustomer();
    }

    /**
     * Get Orders
     *
     * @return \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrders()
    {
        $page = ($this->getRequest()->getParam('p')) ? (int) $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? (int) $this->getRequest()->getParam('limit') : 10;

        $customerId = $this->customerSession->getCustomerId();
        $orderCollection = $this->orderCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('created_at', 'desc');

        $orderCollection->setCurPage($page);
        $orderCollection->setPageSize($pageSize);

        return $orderCollection;
    }

    /**
     * Get Pager Html
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
