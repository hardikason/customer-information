<?php
namespace SK\CustomerInformation\Block\Account;

use Magento\Framework\View\Element\Html\Link;
use Magento\Framework\App\DefaultPathInterface;
use SK\CustomerInformation\Helper\Data as HelperData;

class NavigationLink extends Link\Current
{
    /**
     * @var HelperData
     */
    protected $helperData;
    /**
     * @var DefaultPathInterface
     */
    protected $helpdefaultPathInterfaceerData;

    /**
     * __construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param DefaultPathInterface $defaultPathInterface
     * @param HelperData $helperData
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        DefaultPathInterface $defaultPathInterface,
        HelperData $helperData,
        array $data = []
    ) {
        $this->helperData = $helperData;
        parent::__construct($context, $defaultPathInterface, $data);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $isEnabled = $this->helperData->getConfigValue(HelperData::XML_PATH_MODULE_ENABLE);
        if ($isEnabled) {
            return parent::_toHtml();
        }
        return '';
    }
}
