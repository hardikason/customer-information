<?php
namespace SK\ShippingDeliveryDate\Plugin\Model;

use Magento\Quote\Api\Data\AddressExtensionFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\ShippingMethodManagementInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;

class SaveDeliveryDateToQuote
{
    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var AddressExtensionFactory
     */
    protected $addressExtensionFactory;

    /**
     *
     * @var ShippingMethodManagementInterface
     */
    protected $shippingMethodManagementInterface;

    /**
     * Constructor
     *
     * @param CartRepositoryInterface $cartRepository
     * @param AddressExtensionFactory $addressExtensionFactory
     * @param ShippingMethodManagementInterface $shippingMethodManagementInterface
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        AddressExtensionFactory $addressExtensionFactory,
        ShippingMethodManagementInterface $shippingMethodManagementInterface
    ) {
        $this->cartRepository = $cartRepository;
        $this->addressExtensionFactory = $addressExtensionFactory;
        $this->shippingMethodManagementInterface = $shippingMethodManagementInterface;
    }

    /**
     * Apply shipping delivery date after shipping method selected
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param \Magento\Quote\Model\ShippingMethodManagement $subject
     * @param void $result
     * @param int $cartId The shopping cart ID.
     * @param string $carrierCode The carrier code.
     * @param string $methodCode The shipping method code.
     * @return void
     */
    public function afterApply(
        \Magento\Quote\Model\ShippingMethodManagement $subject,
        $result,
        $cartId,
        $carrierCode,
        $methodCode
    ) {
        $quote = $this->cartRepository->getActive($cartId);

        if ($quote) {
            $rate = $this->shippingMethodManagementInterface->get($cartId);
            if ($rate) {
                $deliveryDate = $rate->getExtensionAttributes()->getShippingDeliveryDate();

                $shippingAddress = $quote->getShippingAddress();
                $shippingAddress->setShippingDeliveryDate($deliveryDate);
            }
        }
    }
}
