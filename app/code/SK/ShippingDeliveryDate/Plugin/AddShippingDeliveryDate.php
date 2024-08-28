<?php
namespace SK\ShippingDeliveryDate\Plugin;

use Magento\Quote\Model\Cart\ShippingMethodConverter;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Api\Data\ShippingMethodExtensionFactory;
use SK\ShippingDeliveryDate\Helper\Data;
use Psr\Log\LoggerInterface;

class AddShippingDeliveryDate
{
    /**
     * @var ShippingMethodExtensionFactory
     */
    protected $extensionFactory;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @param ShippingMethodExtensionFactory $extensionFactory
     * @param Data $helperData
     * @param LoggerInterface $logger
     */
    public function __construct(
        ShippingMethodExtensionFactory $extensionFactory,
        Data $helperData,
        LoggerInterface $logger
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->helperData = $helperData;
        $this->logger = $logger;
    }

    /**
     * Add delivery date information to the carrier data object
     *
     * @param ShippingMethodConverter $subject
     * @param ShippingMethodInterface $result
     * @return ShippingMethodInterface
     */
    public function afterModelToDataObject(ShippingMethodConverter $subject, ShippingMethodInterface $result)
    {
        try {
            $extensibleAttribute =  ($result->getExtensionAttributes())
                    ? $result->getExtensionAttributes()
                    : $this->extensionFactory->create();

            $displayExpectedDelivery = $this->helperData->getConfigValue(Data::XML_PATH_DISPLAY_EXPECTED_DELIVERY);
            if (!$displayExpectedDelivery) {

                $expectedDeliveryDate = '';

            } else {

                $carrierCode = $result->getCarrierCode();

                $warehouseoffDays = $this->helperData->getConfigValue(DATA::XML_PATH_WAREHOUSE_OFF_DAYS);
                $warehouseCutoffTime = $this->helperData->getConfigValue(DATA::XML_PATH_WAREHOUSE_CUTOFF_TIME);
                $warehouseCutoffTime =  date("H:i", strtotime($warehouseCutoffTime));

                $shippingMethodOffDays = $this->helperData->getShippingMethodOffDays($carrierCode) ?: '';
                $shippingMethodDeliveryDays = $this->helperData->getShippingMethodDeliveryDays($carrierCode) ?: '';

                //Check warehouse cut off time with current time and adjust day with 1 if time passed
                $currentTime = $this->helperData->getDateTimeByStoreTimezone("H:i");

                $today = $this->helperData->getDateTimeByStoreTimezone("Y-m-d");

                $warehouseDispatchDate = $this->helperData
                                     ->getExpectedDeliveryDate(0, $warehouseoffDays, $today);

                if ($currentTime > $warehouseCutoffTime) {
                    // add extra day after cut off time
                    $warehouseDispatchDate = $this->helperData
                                     ->getExpectedDeliveryDate(1, $warehouseoffDays, $warehouseDispatchDate);
                }

                $expectedDeliveryDate = $this->helperData->getExpectedDeliveryDate(
                    $shippingMethodDeliveryDays,
                    $shippingMethodOffDays,
                    $warehouseDispatchDate
                );

            }

            $extensibleAttribute->setShippingDeliveryDate($expectedDeliveryDate);

            $result->setExtensionAttributes($extensibleAttribute);

            return $result;

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
