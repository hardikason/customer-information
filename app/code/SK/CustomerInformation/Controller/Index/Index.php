<?php
namespace SK\CustomerInformation\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Result\PageFactory;
use SK\CustomerInformation\Helper\Data as HelperData;

class Index extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param PageFactory $pageFactory
     * @param CustomerSession $customerSession
     * @param HelperData $helperData
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        HelperData $helperData
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $isEnabled = $this->helperData->getConfigValue(HelperData::XML_PATH_MODULE_ENABLE);
        if (!$this->customerSession->isLoggedIn()) {
            return $this->_redirect('customer/account/login');
        } elseif (!$isEnabled) {
            return $this->_redirect('customer/account');
        } else {
            return $this->pageFactory->create();
        }
    }
}
