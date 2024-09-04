<?php
namespace SK\CustomerInformation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{

    public const XML_PATH_MODULE_ENABLE = 'customer/information/enable';
    public const XML_PATH_ALLOW_TO_REMOVE = 'customer/information/allow_to_remove';
    public const XML_PATH_EMAIL_TEMPLATE_TO_REMOVE_REQUEST = 'customer/information/email_template_to_remove_request';
    public const XML_PATH_REQUEST_EMAIL_TO = 'customer/information/request_email_to';
    public const XML_PATH_REQUEST_EMAIL_TO_NAME = 'customer/information/request_email_to_name';
    public const XML_PATH_REQUEST_EMAIL_SUBJECT = 'customer/information/request_email_subject';
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Get any system config value by path
     *
     * @param string $path
     * @param string|null $storeId
     * @return mixed
     */
    public function getConfigValue($path, $storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
