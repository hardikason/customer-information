<?php
namespace SK\CustomerInformation\Model;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use SK\CustomerInformation\Helper\Data as HelperData;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Email
{
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;
    /**
     * @var HelperData
     */
    protected $helperData;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepositoryInterface;

    /**
     * __construct
     *
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param HelperData $helperData
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        HelperData $helperData,
        CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->helperData = $helperData;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }

    /**
     * Send Remove Customer Information Request Email
     *
     * @param  mixed $customerId
     * @param  mixed $orderId
     * @return void
     */
    public function sendRemoveRequestEmail($customerId, $orderId = null)
    {
        $this->inlineTranslation->suspend();

        $store = $this->storeManager->getStore();

        $customer = $this->customerRepositoryInterface->getById($customerId);
        $templateId = $this->helperData->getConfigValue(HelperData::XML_PATH_EMAIL_TEMPLATE_TO_REMOVE_REQUEST);
        $emailTo = $this->helperData->getConfigValue(HelperData::XML_PATH_REQUEST_EMAIL_TO);
        $emailToName = $this->helperData->getConfigValue(HelperData::XML_PATH_REQUEST_EMAIL_TO_NAME);
        $subject = $this->helperData->getConfigValue(HelperData::XML_PATH_REQUEST_EMAIL_SUBJECT);

        // template variables pass here
        $templateVars = [
            'subject' => $subject,
            'emailTo' => $emailTo,
            'emailToName' => $emailToName,
            'customer' => [
                'name' => $customer->getFirstname().' '.$customer->getLastname(),
                'email' => $customer->getEmail()
                ],
            'orderId' => $orderId
        ];

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store->getId()])
            ->setTemplateVars($templateVars)
            ->setFromByScope('general', $store->getId())
            ->addTo($emailTo, $emailToName)
            ->getTransport();

        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}
