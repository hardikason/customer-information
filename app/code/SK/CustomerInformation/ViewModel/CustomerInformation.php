<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 * @author Sonali Kosrabe <sonalikosrabe@outlook.com>
 */

namespace SK\CustomerInformation\ViewModel;

use SK\CustomerInformation\Helper\Data as HelperData;
use Magento\Customer\Model\Address\Config as AddressConfig;

class CustomerInformation implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    /**
     * @var HelperData
     */
    protected $helperData;
    /**
     * @var AddressConfig
     */
    protected $addressConfig;

    /**
     * @param HelperData $helperData
     * @param AddressConfig $addressConfig
     */
    public function __construct(
        HelperData $helperData,
        AddressConfig $addressConfig
    ) {
        $this->helperData = $helperData;
        $this->addressConfig = $addressConfig;
    }

    /**
     * Get Allow To Remove Request
     *
     * @return mixed
     */
    public function getAllowToRemoveRequest() : mixed
    {
        return $this->helperData->getConfigValue(HelperData::XML_PATH_ALLOW_TO_REMOVE);
    }

    /**
     * Get Address in Html Format
     *
     * @param  mixed $order
     * @return mixed
     */
    public function getAddressHtml($order)
    {
        $address = $order->getShippingAddress();
        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();
        return $renderer->renderArray($address);
    }
}
