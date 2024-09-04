<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SK\CustomerInformation\Model\Config\Source\Email;

/**
 * Source for template
 *
 * @api
 * @since 100.0.2
 */
class Template extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var \Magento\Email\Model\Template\Config
     */
    private $_emailConfig;

    /**
     * @param \Magento\Email\Model\Template\Config $emailConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Email\Model\Template\Config $emailConfig,
        array $data = []
    ) {
        parent::__construct($data);
        $this->_emailConfig = $emailConfig;
    }

    /**
     * Generate list of email templates
     *
     * @return array
     */
    public function toOptionArray()
    {
        $emailTemplates = $this->_emailConfig->getAvailableTemplates();
        return $emailTemplates;
    }
}
