<?php
namespace SK\ShippingDeliveryDate\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Data extends AbstractHelper
{
    public const XML_PATH_DISPLAY_EXPECTED_DELIVERY = 'carriers/general/display_expected_delivery';
    public const XML_PATH_BANK_HOLIDAYS = 'carriers/general/bank_holidays';
    public const XML_PATH_WAREHOUSE_OFF_DAYS = 'carriers/warehouse/off_days';
    public const XML_PATH_WAREHOUSE_CUTOFF_TIME = 'carriers/warehouse/cutoff_time';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        TimezoneInterface $timezone
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->timezone = $timezone;
    }

    /**
     * Get DateTime By Store Timezone
     *
     * @param  mixed $format
     * @param  mixed $addDays
     * @param  mixed $storeId
     * @return string
     */
    public function getDateTimeByStoreTimezone($format = 'Y-m-d H:i:s', $addDays = null, $storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        $storeTimezone = $this->timezone->getConfigTimezone(ScopeInterface::SCOPE_STORE, $storeId);

        $date = new \DateTime('now', new \DateTimeZone($storeTimezone));

        if ($addDays) {

            $dayString = $addDays < 2 ? 'day' : 'days';
            // Add day(s) to the current time
            $date->modify('+'.$addDays.' '.$dayString);
        }

        return $date->format($format);
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
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Shipping Method OffDays
     *
     * @param  mixed $carrierCode
     * @param  mixed $storeId
     * @return mixed
     */
    public function getShippingMethodOffDays($carrierCode, $storeId = null) : mixed
    {

        $path = 'carriers/'.$carrierCode.'/off_days';

        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Shipping Method DeliveryDays
     *
     * @param  mixed $carrierCode
     * @param  mixed $storeId
     * @return mixed
     */
    public function getShippingMethodDeliveryDays($carrierCode, $storeId = null) : mixed
    {
        $path = 'carriers/'.$carrierCode.'/delivery_days';

        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Expected DeliveryDate
     *
     * @param  mixed $deliveryDays
     * @param  mixed $offDays
     * @param  mixed $warehouseDispatchDate
     * @param  mixed $storeId
     * @return string
     */
    public function getExpectedDeliveryDate(
        $deliveryDays = 0,
        $offDays = null,
        $warehouseDispatchDate = null,
        $storeId = null
    ) {
        $storeTimezone = $this->timezone->getConfigTimezone(ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $storeId);

        // Create a DateTime object with the store's timezone
        $date = new \DateTime('now', new \DateTimeZone($storeTimezone));

        if ($warehouseDispatchDate) {
            $date = new \DateTime($warehouseDispatchDate, new \DateTimeZone($storeTimezone));
        }

        $offDays = explode(',', $offDays??'');

        $bankHolidays = $this->getConfigValue(self::XML_PATH_BANK_HOLIDAYS);
        $bankHolidays = preg_split("/\r\n|\n|\r/", $bankHolidays??'');

        $daysAdded = 0;

        while ($daysAdded < $deliveryDays) {

            $date->modify('+1 day');

            $dayOfWeek = $date->format('w');

            // Check if the day is a valid delivery day and not a day off or a bank holiday
            if (in_array($dayOfWeek, $offDays) || in_array($date->format('d/m/Y'), $bankHolidays)) {
                continue;
            }

            $daysAdded++;

        }

        return $date->format('Y-m-d');
    }
}
