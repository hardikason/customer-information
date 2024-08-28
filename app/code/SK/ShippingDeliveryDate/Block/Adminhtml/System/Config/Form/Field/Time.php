<?php
namespace SK\ShippingDeliveryDate\Block\Adminhtml\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;

class Time extends Field
{
    /**
     * Render time field with AM/PM
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $value = $element->getValue();
        $time = $amPm = '';

        if ($value) {
            list($time, $amPm) = explode(' ', $value);
        }

        $html = '<input type="text" name="' . $element->getName() . '[time]"
                                value="' . $time . '" style="width:50px;" />';
        $html .= '<select name="' . $element->getName() . '[am_pm]" style="width:50px;">';
        $html .= '<option value="AM"' . ($amPm == 'AM' ? ' selected="selected"' : '') . '>AM</option>';
        $html .= '<option value="PM"' . ($amPm == 'PM' ? ' selected="selected"' : '') . '>PM</option>';
        $html .= '</select>';

        return $html;
    }
}
