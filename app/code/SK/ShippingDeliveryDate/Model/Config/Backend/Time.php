<?php
namespace SK\ShippingDeliveryDate\Model\Config\Backend;

use Magento\Framework\App\Config\Value;

class Time extends Value
{

    /**
     * Before System Config Save
     *
     * @return void
     */
    public function beforeSave()
    {
        $time = $this->getValue();
        if (is_array($time)) {
            $this->setValue($time['time'] . ' ' . $time['am_pm']);
        }
    }
}
