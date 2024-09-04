<?php
namespace SK\CustomerInformation\Block\Account;

use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use SK\CustomerInformation\ViewModel\CustomerInformation as CustomerInformationViewModel;

class Info extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var CustomerInformationViewModel
     */
    protected $customerInformationViewModel;

    /**
     * __construct
     *
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param CustomerInformationViewModel $customerInformationViewModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        CustomerInformationViewModel $customerInformationViewModel,
        array $data = []
    ) {

        $this->customerSession = $customerSession;
        $this->customerInformationViewModel = $customerInformationViewModel;

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

        $customerId = $this->customerSession->getCustomer()->getId();

        $orders = $this->customerInformationViewModel->getCustomerOrders($customerId);

        if ($orders->getSize()) {
            $pager = $this->getLayout()->createBlock(\Magento\Theme\Block\Html\Pager::class, 'customer.orders.pager')
                ->setShowPerPage(true)
                ->setCollection($orders);
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
     * Get Pager Html
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
